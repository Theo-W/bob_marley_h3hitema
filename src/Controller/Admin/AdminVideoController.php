<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
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
#[Route('/admin/video', name: 'admin.video.')]
class AdminVideoController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileServiceInterface   $fileService,
        private AlertInterface         $alert,
        private string                 $uploadDirectory,
        private PaginatorInterface     $paginator,
        private VideoRepository        $videoRepository
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $query = $this->videoRepository->findAll();
        $paginator = $this->paginator->paginate($query, 1, 5);

        return $this->render('admin/video/index.html.twig', [
            'videos' => $paginator,
        ]);
    }

    #[Route('/create', name: 'new')]
    public function create(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateVideo = $form->get('video')->getData();
            if ($dateVideo) {
                $fileVideo = $this->fileService->upload($dateVideo, $this->uploadDirectory);
                $video->setVideo($fileVideo);
            }

            $this->em->persist($video);
            $this->em->flush();

            $this->alert->success("Votre vidéo à bien été créer");
            return $this->redirectToRoute('admin.video.index');
        }

        return $this->render('admin/video/create.html.twig', [
            'video' => $video,
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Video $video): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateVideo = $form->get('video')->getData();
            if ($dateVideo) {
                $fileVideo = $this->fileService->upload($dateVideo, $this->uploadDirectory);
                $video->setVideo($fileVideo);
            }

            $this->em->persist($video);
            $this->em->flush();

            $this->alert->success("Votre image à bien été modifier");
            return $this->redirectToRoute('admin.video.index');
        }

        return $this->render('admin/video/edit.html.twig', [
            'video' => $video,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'POST')]
    public function delete(Request $request, Video $video): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->get('_token'))) {

            $this->em->remove($video);
            $this->em->flush();
            $this->alert->success('votre images à bien été supprimer');
        }

        return $this->redirectToRoute('admin.video.index');
    }

    #[Route('/delete/video/{id}', name: 'delete.video')]
    public function deleteImage(int $id)
    {
        $videofind = $this->videoRepository->find($id);

        $image = $videofind->getVideo();
        $this->fileService->delete($image, $this->uploadDirectory);

        $videofind->setVideo(null);
        $this->em->flush();

        $this->alert->success('votre image à bien été supprimer');

        return $this->redirectToRoute('admin.video.edit', [
            'id' => $videofind->getId(),
        ]);
    }
}
