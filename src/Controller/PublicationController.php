<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PublicationController extends AbstractController
{
    #[Route('/publication/{id}', name: 'app_show_publication')]
    public function show(
        string                $id,
        PublicationRepository $publicationRepository,
//        CommentRepository      $commentRepository,
//        Request                $request,
//        EntityManagerInterface $entityManager,
    ): Response
    {
        $publication = $publicationRepository->findOneBy(['id' => $id]);
        if ($publication === null) {
            $this->addFlash('danger', 'Cette publication n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('publication/index.html.twig', [
            'id' => $publication->getId(),
            'publication' => $publication,
        ]);
    }

    #[Route('/genre/{slug}', name: 'app_category')]
    public function publicationCategory(
        string             $slug,
        CategoryRepository $categoryRepository,
    ): Response
    {
        $category = $categoryRepository->findFullBySlug($slug);
        if ($category === null) {
            $this->addFlash('danger', 'Ce genre n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('publication/publications_by_category.html.twig', [
            'category' => $category,
        ]);
    }
}
