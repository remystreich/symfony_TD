<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddBookController extends AbstractController
{
    #[Route('/books/add', name: 'book_add')]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setAddedAt(new \DateTimeImmutable());
            $book->setUpdatedAt(new \DateTimeImmutable());
            $book->setAddedBy($this->getUser());

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('add_book/index.html.twig', [
            'bookForm' => $form->createView(),
        ]);
    }
}
