<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowListBookController extends AbstractController
{
    #[Route('/books', name: 'book_index')]
    public function __invoke(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('show_list_book/index.html.twig', [
            'books' => $books,
        ]);
    }
}
