<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookDetailController extends AbstractController
{
    #[Route('/book/{id}', name: 'book_detail')]
    public function __invoke(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);




        return $this->render('book_detail/index.html.twig', [
            'book' => $book,
        ]);
    }
}
