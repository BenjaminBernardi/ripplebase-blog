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
        return substr($description, 0, 50) . '...';
    }
}
