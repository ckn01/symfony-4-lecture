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
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;
    private $tokenGenerator;
    private $mailer;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        \Swift_Mailer $mailer
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userRegistered', EventPriorities::PRE_WRITE]
        ];
    }

    public function userRegistered(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST])){
            return;
        }

        // It is on User, we need to hash password here
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );

        // Create confirmation token
        $user->setConfirmationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );

        // Send e-mail here...
        $message = (new \Swift_Message())
            ->setFrom('ujefaisal@gmail.com')
            ->setTo('faisal.uje@gmail.com')
            ->setBody('Hello, how are you?');

        $this->mailer->send($message);
    }
}