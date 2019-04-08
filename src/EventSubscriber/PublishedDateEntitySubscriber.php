<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 12/03/19
 * Time: 11:33
 */

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthoredEntityInterface;
use App\Entity\PublishedDateEntityInterface;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PublishedDateEntitySubscriber implements EventSubscriberInterface
{
   public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_WRITE]
        ];
    }

    public function setDatePublished(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$entity instanceof PublishedDateEntityInterface || Request::METHOD_POST !== $method) {
            return;
        }

        $entity->setPublished(new \DateTime());
    }
}