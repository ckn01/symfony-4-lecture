<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 12/03/19
 * Time: 18:01
 */

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $author): AuthoredEntityInterface;
}