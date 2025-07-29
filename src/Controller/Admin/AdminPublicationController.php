<?php

namespace App\Controller\Admin;

use App\Entity\Publication;
use App\Form\AddPublicationForm;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPublicationController extends AbstractController
{
    #[Route('/admin/publication', name: 'app_admin_publication')]
    public function index(
        PublicationRepository $publicationRepository,
        PaginatorInterface    $paginator,
        Request               $request
    ): Response
    {
        $publications = $paginator->paginate(
            $publicationRepository->getAll(), // Join Category Order by releasedAt DESC
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin_publication/index.html.twig', [
            'controller_name' => 'AdminPublicationController',
            'publications' => $publications
        ]);
    }

    #[Route('/admin/publication/ajouter', name: 'app_admin_publication_add', methods: ['GET', 'POST'])]
    public function addPublication(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $publication = new Publication();
        $form = $this->createForm(AddPublicationForm::class, $publication);
        $form->handleRequest($request);
        dump($publication);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication->setCreatedAt(new \DateTime());
            $entityManager->persist($publication);
            $entityManager->flush();

            $this->addFlash('success', 'Publication ajoutée à la base !');
            return $this->redirectToRoute('app_admin_publication');
        }

        return $this->render('admin_publication/add_publication.html.twig', [
            'form' => $form,
        ]);
    }
}
