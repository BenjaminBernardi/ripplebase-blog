<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class TextExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function shortDescription(string $description): string
    {
        if (strlen($description) >= 80) {
            return substr($description, 0, 80) . '...';
        }

        return $description;
    }
}
