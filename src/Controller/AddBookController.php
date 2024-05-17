<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Service\GoogleBookApiService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddBookController extends AbstractController
{
    #[Route('/books/add', name: 'book_add')]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager, GoogleBookApiService $googleBookApiService): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jsonResponse = $googleBookApiService->searchBooks($book->getTitle(), $book->getAuthor(), 'en');
            if ($jsonResponse) {
                $data = json_decode($jsonResponse);

                if (isset($data->items[0]->volumeInfo) && $data->totalItems>0) {
                    $volumeInfo = $data->items[0]->volumeInfo;
                    $book->setTitle($volumeInfo->title );
                    $book->setAuthor($volumeInfo->authors[0] );
                    $book->setPublicationDate(new \DateTime($volumeInfo->publishedDate));
                    $book->setIsbn($volumeInfo->industryIdentifiers[0]->identifier );
                } else {
                    $error = 'No book found with the provided title and author.';
                    return $this->render('add_book/index.html.twig', [
                        'bookForm' => $form->createView(),
                        'error' => $error,
                    ]);
                }
            }

            $book->setAddedAt(new \DateTimeImmutable());
            $book->setUpdatedAt(new \DateTimeImmutable());
            $book->setAddedBy($this->getUser());

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }


        return $this->render('add_book/index.html.twig', [
            'bookForm' => $form->createView(),
            'error' => null
        ]);
    }
}
