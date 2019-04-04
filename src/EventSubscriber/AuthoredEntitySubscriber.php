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
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthoredEntitySubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return;
        }

        /** @var User $author */
        $author = $token->getUser();

        if(!$entity instanceof AuthoredEntityInterface || Request::METHOD_POST !== $method) {
            return;
        }

        $entity->setAuthor($author);
    }
}