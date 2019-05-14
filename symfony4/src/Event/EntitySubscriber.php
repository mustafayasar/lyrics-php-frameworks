<?php
namespace App\Event;

use App\Entity\Singer;
use App\Entity\Song;
use App\Utils\SearchItems;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\Events;

class EntitySubscriber implements EventSubscriber
{
    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::preRemove,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->insertOrUpdateElastic($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->insertOrUpdateElastic($args);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        $search_items   = new SearchItems();

        if ($entity instanceof Singer) {
            $search_items->deleteItem($entity->getId(), 'singer');

        } elseif ($entity instanceof Song) {
            $search_items->deleteItem($entity->getId(), 'song');
        }
    }

    public function insertOrUpdateElastic(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        $search_items   = new SearchItems();

        if ($entity instanceof Singer) {
            $search_items->saveItem($entity->getId(), 'singer',
                $this->urlGenerator->generate('singer_songs', ['singer_slug' => $entity->getSlug()]),
                $entity->getName(),
                '',
                $entity->getStatus());

        } elseif ($entity instanceof Song) {
            $search_items->saveItem($entity->getId(), 'song',
                $this->urlGenerator->generate('song_view', ['singer_slug' => $entity->getSinger()->getSlug(), 'song_slug' => $entity->getSlug()]),
                $entity->getTitle().' - '.$entity->getSinger()->getName(),
                $entity->getLyrics(),
                $entity->getStatus());
        }
    }
}