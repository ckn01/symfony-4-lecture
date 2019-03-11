<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $faker;
    private const USERS = ['user_admin', 'user_other'];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $users = self::USERS;

        for ($i=0;$i<100;$i++) {
            shuffle($users);
            $user = $this->getReference(reset($users));

            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(20))
                ->setContent($this->faker->realText())
                ->setPublished($this->faker->dateTimeThisCentury)
                ->setAuthor($user)
                ->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        $users = self::USERS;

        for ($i=0;$i<100;$i++) {
            for ($j=0;$j<rand(1, 10);$j++) {
                shuffle($users);
                $user = $this->getReference(reset($users));

                $comment = new Comment();
                $comment->setContent($this->faker->realText())
                        ->setPublished($this->faker->dateTimeThisYear)
                        ->setAuthor($user)
                        ->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin')
            ->setEmail('admin@blog.co')
            ->setName('Admin')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'Admin123'
            ));

        $this->addReference('user_admin', $user);

        $manager->persist($user);

        $user = new User();
        $user->setUsername('faisaluje')
            ->setEmail('faisaluje@blog.co')
            ->setName('Faisal Uje')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'Faisaluje123'
            ));

        $this->addReference('user_other', $user);

        $manager->persist($user);

        $manager->flush();
    }
}
