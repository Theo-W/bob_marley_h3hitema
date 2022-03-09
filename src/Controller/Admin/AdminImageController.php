<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Form\ImageType;
use App\Repository\ImagesRepository;
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
#[Route('/admin/image', name: 'admin.image.')]
class AdminImageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileServiceInterface $fileService,
        private AlertInterface $alert,
        private string $uploadDirectory,
        private PaginatorInterface $paginator,
        private ImagesRepository $imagesRepository
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $query = $this->imagesRepository->findAll();
        $paginator = $this->paginator->paginate($query, 1, 5);

        return $this->render('admin/image/index.html.twig', [
            'images' => $paginator,
        ]);
    }

    #[Route('/create', name: 'new')]
    public function create(Request $request): Response
    {
        $image = new Images();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();
            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $image->setImage($fileImage);
            }

            $this->em->persist($image);
            $this->em->flush();

            $this->alert->success("Votre image à bien été créer");
            return $this->redirectToRoute('admin.image.index');
        }

        return $this->render('admin/image/create.html.twig', [
            'images' => $image,
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Images $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();
            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $image->setImage($fileImage);
            }

            $this->em->persist($image);
            $this->em->flush();

            $this->alert->success("Votre image à bien été modifier");
            return $this->redirectToRoute('admin.image.index');
        }

        return $this->render('admin/image/edit.html.twig', [
            'images' => $image,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'POST')]
    public function delete(Request $request, Images $images): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $images->getId(), $request->get('_token'))) {

            $this->em->remove($images);
            $this->em->flush();
            $this->alert->success('votre images à bien été supprimer');
        }

        return $this->redirectToRoute('admin.image.index');
    }

    #[Route('/delete/image/{id}', name: 'delete.image')]
    public function deleteImage(int $id)
    {
        $imagefind = $this->imagesRepository->find($id);

        $image = $imagefind->getImage();
        $this->fileService->delete($image, $this->uploadDirectory);

        $imagefind->setImage(null);
        $this->em->flush();

        $this->alert->success('votre image à bien été supprimer');

        return $this->redirectToRoute('admin.image.edit', [
            'id' => $imagefind->getId(),
        ]);
    }
}
