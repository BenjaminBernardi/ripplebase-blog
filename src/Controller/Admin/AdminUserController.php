<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminUserController extends AbstractController
{
    #[Route('/admin/utilisateur', name: 'app_admin_user')]
    public function index(
        UserRepository     $userRepository,
        PaginatorInterface $paginator,
        Request            $request
    ): Response
    {
        $users = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            25
        );

        return $this->render('Admin/admin_user/index.html.twig', [
            'controller_name' => 'AdminUserController',
            'users' => $users
        ]);
    }

    #[Route('/admin/utilisateur/supprimer/{id}', name: 'app_admin_user_delete', methods: ['GET', 'POST'])]
    public function deletePublication(
        string                 $id,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        if ($user === null) {
            $this->addFlash('danger', 'Cet utilisateur n\'existe pas !');
            return $this->redirectToRoute('app_admin_user');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_user');
    }
}
