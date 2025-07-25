<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AddCommentForm;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PublicationRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublicationController extends AbstractController
{
    #[Route('/publication/{id}', name: 'app_show_publication')]
    public function show(
        string                 $id,
        PublicationRepository  $publicationRepository,
        CommentRepository      $commentRepository,
        RatingRepository       $ratingRepository,
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $publication = $publicationRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['publication' => $id], ['createdAt' => 'DESC']);
        $avgRatings = $ratingRepository->getAvgRatings($id);

        if ($publication === null) {
            $this->addFlash('danger', 'Cette publication n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        $comment = new Comment();
        $form = $this->createForm(AddCommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPublication($publication);
            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setCreatedAt(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_publication', [
                'id' => $publication->getId()
            ]);
        }

        return $this->render('publication/index.html.twig', [
            'id' => $publication->getId(),
            'form' => $form,
            'publication' => $publication,
            'comments' => $comments,
            'avgRatings' => $avgRatings,
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
