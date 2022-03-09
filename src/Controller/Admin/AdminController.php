<?php

namespace App\Controller\Admin;

use App\Repository\ContactRepository;
use App\Repository\ImagesRepository;
use App\Repository\NewsRepository;
use App\Repository\VideoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/admin', name: 'admin.')]
class AdminController extends AbstractController
{

    public function __construct(
        private ContactRepository $contactRepository,
        private ImagesRepository $imagesRepository,
        private VideoRepository $videoRepository,
        private NewsRepository $newsRepository
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'messages' => $this->contactRepository->findLast(3),
            'messagesall' => $this->contactRepository->findAll(),
            'images' => $this->imagesRepository->findAll(),
            'videos' => $this->videoRepository->findAll(),
            'news' => $this->newsRepository->findAll(),
        ]);
    }
}
