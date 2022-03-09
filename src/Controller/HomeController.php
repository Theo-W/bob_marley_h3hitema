<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\ImagesRepository;
use App\Repository\NewsRepository;
use App\Repository\VideoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private NewsRepository $newsRepository,
        private ImagesRepository $imagesRepository,
        private VideoRepository $videoRepository
    )
    {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/news', name: 'news')]
    public function news(): Response
    {
        return $this->render('home/news/index.html.twig', [
            'news' => $this->newsRepository->findAll()
        ]);
    }

    #[Route('/news/{id}', name: 'news.show')]
    public function show(int $id): Response
    {
        return $this->render('home/news/show.html.twig', [
            'news' => $this->newsRepository->find($id)
        ]);
    }

    #[Route('/discography', name: 'discography')]
    public function discography(): Response
    {
        return $this->render('home/discography.html.twig');
    }

    #[Route('/video', name: 'video')]
    public function video(): Response
    {
        return $this->render('home/video.html.twig', [
            'videos' => $this->videoRepository->findAll()
        ]);
    }

    #[Route('/image', name: 'image')]
    public function image(): Response
    {
        return $this->render('home/image.html.twig', [
            'images' => $this->imagesRepository->findAll()
        ]);
    }
}
