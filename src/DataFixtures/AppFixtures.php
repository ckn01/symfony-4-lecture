<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference('user_admin');

        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!')
            ->setContent('Post text!!')
            ->setPublished(new \DateTime(date('Y-m-d H:i:s')))
            ->setAuthor($user)
            ->setSlug('a-first-post');
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A Second Post!')
            ->setContent('Post text!!')
            ->setPublished(new \DateTime(date('Y-m-d H:i:s')))
            ->setAuthor($user)
            ->setSlug('a-second-post');
        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin')
            ->setEmail('admin@blog.co')
            ->setName('Faisal Uje')
            ->setPassword('admin123');

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
