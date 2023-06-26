<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AlbumType;
use App\Form\AlbumSearchType;
use Doctrine\ORM\EntityManagerInterface;

class AlbumController extends AbstractController
{
        public function __construct(
            private AlbumRepository $albumRepository,
            private SongRepository $songRepository,
            private EntityManagerInterface $entityManager) {       
          }

    #[Route('/', name: 'app_album')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(AlbumSearchType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $datas = $form->getData();
            $mySearch = $datas['title'];
            $albumEntity = $this->albumRepository->findBy(['title' => $mySearch]);
        } else {
        $albumEntity = $this->albumRepository->findAll();}
        return $this->render('album/index.html.twig', [
            'controller_name' => 'AlbumController',
            'albums' => $albumEntity,
            'form' => $form->createView()]);
    }

    #[Route('/album/{albumId}', name: 'app_get_to_album')]
    public function getToAlbum($albumId): Response
    {
        $albumEntity = $this->albumRepository->find($albumId);
        $songsEntities = $albumEntity->getSongs();

        $totalinSeconds = 0;
        foreach ($albumEntity->getSongs() as $song) {
            $totalinSeconds+= $song->getDuration();
            $total = $totalinSeconds / 60;
        }
        return $this->render('album/getToAlbum.html.twig', [
            'controller_name' => 'AlbumController',
            'album' => $albumEntity,
            'songs' => $songsEntities,
            'total' => (round($total, 0))]);
    }
    
    #[Route('/album_add', name: 'app_album_add')]
    public function addAlbum(Request $request): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $this->entityManager->persist($album);
            $this->entityManager->flush();
            $this->redirectToRoute('app_album_add');
        }
        return $this->render('album/addAlbum.html.twig', [
            'controller_name' => 'AlbumController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/album_edit/{albumId}', name: 'app_album_edit')]
    public function editAlbum($albumId,Request $request): Response
    {
        $album = $this->albumRepository->find($albumId);
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $this->entityManager->persist($album);
            $this->entityManager->flush();
            $this->redirectToRoute('app_album');
        }
        return $this->render('album/editAlbum.html.twig', [
            'controller_name' => 'AlbumController',
            'form' => $form->createView(),
        ]);
    }
}