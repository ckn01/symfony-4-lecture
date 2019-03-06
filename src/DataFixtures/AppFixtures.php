<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!')
            ->setContent('Post text!!')
            ->setPublished(new \DateTime(date('Y-m-d H:i:s')))
            ->setAuthor('Faisal Uje')
            ->setSlug('a-first-post');
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A Second Post!')
            ->setContent('Post text!!')
            ->setPublished(new \DateTime(date('Y-m-d H:i:s')))
            ->setAuthor('Faisal Uje')
            ->setSlug('a-second-post');
        $manager->persist($blogPost);

        $manager->flush();
    }
}
