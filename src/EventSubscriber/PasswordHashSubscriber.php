<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 06/03/19
 * Time: 21:52
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordHashSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function hashPassword(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])){
            return;
        }

        // It is on User, we need to hash password here
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }
}