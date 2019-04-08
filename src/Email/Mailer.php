<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 20/03/19
 * Time: 16:06
 */

namespace App\Email;

use App\Entity\User;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'email/confirmation.html.twig',
            ['user' => $user]
        );

        $message = $message = (new \Swift_Message('Please confirm your account!'))
            ->setFrom('ujefaisal@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}