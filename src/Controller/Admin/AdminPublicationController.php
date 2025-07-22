<?php

namespace App\Controller\Admin;

use App\Repository\PublicationRepository;
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
        $publication = $paginator->paginate(
            $publicationRepository->getAll(), // Order by id DESC
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin_publication/index.html.twig', [
            'controller_name' => 'AdminPublicationController',
            'publication' => $publication
        ]);
    }
}
