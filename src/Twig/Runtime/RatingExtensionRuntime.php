<?php

namespace App\Twig\Runtime;

use App\Entity\Publication;
use App\Entity\User;
use Twig\Extension\RuntimeExtensionInterface;

class RatingExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function hasUserRatePublication(User $user, Publication $publication): bool
    {
        $ratings = $user->getRatings();
        foreach ($ratings as $rating){
            if ($rating->getPublication()->getId() == $publication->getId()){
                return true;
            }
        }
        return false;
    }
}
