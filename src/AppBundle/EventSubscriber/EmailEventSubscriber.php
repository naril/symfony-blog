<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Comment;
use AppBundle\Service\NotifyService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailEventSubscriber implements EventSubscriber
{
    private $notifyService;

    public function __construct(NotifyService $notifyService)
    {
        $this->notifyService = $notifyService;
    }

    public function getSubscribedEvents()
    {
        return ["postPersist"];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $this->notifyService->send($entity);
    }
}