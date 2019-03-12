<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 12/03/19
 * Time: 18:02
 */

namespace App\Entity;


interface PublishedDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface;
}