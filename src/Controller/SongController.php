<?php

namespace App\Controller;

use App\Entity\Song;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SongType;
use Doctrine\ORM\EntityManagerInterface;

class SongController extends AbstractController
{

    public function __construct(
        private SongRepository $songRepository,
        private EntityManagerInterface $entityManager) {       
      }

    
    #[Route('/song', name: 'app_song')]
    public function index(): Response
    {
        return $this->render('song/index.html.twig', [
            'controller_name' => 'SongController',
        ]);
    }

#[Route('/song_add', name: 'app_song_add')]
public function addSong(Request $request): Response
{
    $song = new Song();
    $form = $this->createForm(SongType::class, $song);
    $form->handleRequest($request);
    if($form->isSubmitted()&& $form->isValid()) {
        $this->entityManager->persist($song);
        $this->entityManager->flush();
        $this->redirectToRoute('app_song_add');
    }
    return $this->render('song/addSong.html.twig', [
        'controller_name' => 'SongController',
        'form' => $form->createView(),
    ]);
}
}