<?php

namespace App\Factory;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Publication>
 */
final class PublicationFactory extends PersistentProxyObjectFactory{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Publication::class;
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable    {
        return [
            'createdAt' => self::faker()->dateTime(),
            'description' => self::faker()->text(),
            'title' => self::faker()->text(255),
            'imagePath' => self::faker()->imageUrl(),
            'releasedAt' => self::faker()->dateTimeBetween('+1 week', '+2 week'),
            'categories' => CategoryFactory::random(),
        ];
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Publication $publication): void {})
        ;
    }
}
