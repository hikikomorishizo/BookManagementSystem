<?php

namespace App\EventListener;

use App\Entity\Book;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookEventListener implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::postPersist => 'onPostPersist',
        ];
    }

    public function onPostPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Book) {
            return;
        }

        $this->logger->info('A new book has been added', [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'author' => $entity->getAuthor(),
            'publicationYear' => $entity->getPublicationYear(),
            'isbn' => $entity->getIsbn(),
        ]);
    }
}
