<?php

namespace App\Controller;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;


class GenreController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager){}

    #[Route('/add_genre', name: 'app_genre_add')]
    public function index(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $this->entityManager->persist($genre);
            $this->entityManager->flush();
            $this->redirectToRoute('app_genre_add');
        }
        return $this->render('genre/addGenre.html.twig', [
            'controller_name' => 'GenreController',
            'form' => $form->createView(),
        ]);
    }
}
