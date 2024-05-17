<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteBookController extends AbstractController
{
    #[Route('/books/delete/{id}', name: 'book_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirectToRoute('book_index');
    }
}
