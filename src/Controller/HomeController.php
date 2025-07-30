<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route(name: 'app_home')]
    public function index(
        PublicationRepository $publicationRepository
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $lastPublications = $publicationRepository->findBy(
            [],
            ['releasedAt' => 'DESC'],
            6
        );

        return $this->render('home/index.html.twig', [
            'lastPublications' => $lastPublications,
        ]);
    }
}
