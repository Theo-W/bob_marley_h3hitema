<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Services\AlertInterface;
use App\Services\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/admin/news', name: 'admin.news.')]
class AdminNewsController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private NewsRepository         $newsRepository,
        private AlertInterface         $alert,
        private PaginatorInterface     $paginator,
        private FileServiceInterface   $fileService,
        private string                 $uploadDirectory,
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $query = $this->newsRepository->findAll();
        $pagination = $this->paginator->paginate($query, $request->query->getInt('page', 1), 5);

        return $this->render('admin/news/index.html.twig', [
            'news' => $pagination,
        ]);
    }

    #[Route('/create', name: 'new')]
    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();
            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $news->setImage($fileImage);
            }

            $this->em->persist($news);
            $this->em->flush();

            $this->alert->success('Votre news à bien été créer');
            return $this->redirectToRoute('admin.news.index');
        }
        return $this->render('admin/news/create.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();
            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $news->setImage($fileImage);
            }

            $this->em->persist($news);
            $this->em->flush();

            $this->alert->success('Votre news à bien été modifier');
            return $this->redirectToRoute('admin.news.index');
        }
        return $this->render('admin/news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'POST')]
    public function delete(Request $request, News $news): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->get('_token'))) {

            $this->em->remove($news);
            $this->em->flush();
            $this->alert->success('votre news à bien été supprimer');
        }

        return $this->redirectToRoute('admin.news.index');
    }

    #[Route('/delete/image/{id}', name: 'delete.image')]
    public function deleteImage(int $id): RedirectResponse
    {
        $new = $this->newsRepository->find($id);

        $image = $new->getImage();
        $this->fileService->delete($image, $this->uploadDirectory);

        $new->setImage(null);
        $this->em->flush();

        $this->alert->success('votre image à bien été supprimer');

        return $this->redirectToRoute('admin.news.edit', [
            'id' => $new->getId(),
        ]);
    }
}
