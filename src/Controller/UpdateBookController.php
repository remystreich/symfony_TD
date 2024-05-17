<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\UpdateBookFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UpdateBookController extends AbstractController
{
    #[Route('/update/book/{id}', name: 'update_book')]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(UpdateBookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('update_book/index.html.twig', [
            'bookForm' => $form->createView(),
            'book' => $book,
        ]);
    }
}
