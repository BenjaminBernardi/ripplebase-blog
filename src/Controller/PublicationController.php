<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Rating;
use App\Form\AddCommentForm;
use App\Form\AddRatingForm;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PublicationRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $addComment = $this->createForm(AddCommentForm::class, $comment);
        $addComment->handleRequest($request);

        if ($addComment->isSubmitted() && $addComment->isValid()) {
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

        // $rating = récupère l'entité rating entre une publication et le user connecté
        $userId = $this->getUser()->getId();
        $rating = $ratingRepository->getUserRate($userId, $id);
        // si elle existe : ok on garde
        // SINON : $rating = new Rating();
        if ($rating === null) {
            $rating = new Rating();
        }

        $addRating = $this->createForm(AddRatingForm::class, $rating);
        $addRating->handleRequest($request);

        if ($addRating->isSubmitted() && $addRating->isValid()) {
            $rating->setPublication($publication);
            $user = $this->getUser();
            $rating->setUser($user);
            $entityManager->persist($rating);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_publication', [
                'id' => $publication->getId()
            ]);
        }

        return $this->render('publication/index.html.twig', [
            'id' => $publication->getId(),
            'addComment' => $addComment,
            'addRating' => $addRating,
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

    #[Route('/edit-comment/{id}', name: 'app_edit_comment', methods: ['POST'])]
    public function editComment(
        string                 $id,
        CommentRepository      $commentRepository,
        Request                $request,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface  $generator
    ): JsonResponse
    {
        $redirect = $generator->generate('app_show_publication', ['id' => $id]);
        if (null === $comment = $commentRepository->findOneBy(['id' => $id])) {
            $this->addFlash('danger', 'Ce commentaire n\'existe pas !');
            return new JsonResponse($redirect);
        }

        if ($comment->getUser()->getId() != $this->getUser()->getId()) {
            $this->addFlash('danger', 'Impossible de modifier ce commentaire !');
            return new JsonResponse($redirect);
        }

        $content = json_decode($request->getContent(), true);
        $comment->setDescription($content['description']);
        $entityManager->flush();
        return new JsonResponse(true);
    }

    #[Route('/delete-comment/{id}', name: 'app_delete_comment')]
    public function deleteComment(
        string                 $id,
        CommentRepository      $commentRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $id]);
        if ($comment === null) {
            $this->addFlash('danger', 'Ce commentaire n\'existe pas !');
            return $this->redirectToRoute('app_show_publication');
        }
        if ($comment->getUser()->getId() != $this->getUser()->getId()) {
            $this->addFlash('danger', 'Impossible de supprimer le commentaire !');
            return $this->redirectToRoute('app_show_publication');
        }

        $id = $comment->getPublication()->getId();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_publication', [
            'id' => $id
        ]);
    }
}
