<?php

namespace App\EventSubscriber;

use App\Entity\Book;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'postPersist' => 'onPostPersist',
        ];
    }

    public function onPostPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Book) {
            return;
        }

        $this->logger->info('A new book was added: ' . $entity->getTitle());
    }
}
