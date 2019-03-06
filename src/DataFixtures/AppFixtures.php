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
        $user = $this->getReference('user_admin');

        for ($i=0;$i<100;$i++) {
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
        for ($i=0;$i<100;$i++) {
            for ($j=0;$j<rand(1, 10);$j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText())
                        ->setPublished($this->faker->dateTimeThisYear)
                        ->setAuthor($this->getReference('user_admin'));

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
            ->setName('Faisal Uje')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin123'
            ));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}