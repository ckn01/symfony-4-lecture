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

    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.co',
            'name' => 'Admin',
            'password' => 'Secret123'
        ],
        [
            'username' => 'faisaluje',
            'email' => 'faisaluje@blog.co',
            'name' => 'Faisal Uje',
            'password' => 'Secret123'
        ],
        [
            'username' => 'ahmad',
            'email' => 'ahmad@blog.co',
            'name' => 'Ahmad Apandi',
            'password' => 'Secret123'
        ],
        [
            'username' => 'banu',
            'email' => 'banu@blog.co',
            'name' => 'Banu Supriyadi',
            'password' => 'Secret123'
        ]
    ];

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
        for ($i=0;$i<100;$i++) {
            $authorReference = $this->getRandomUserReference();

            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(20))
                ->setContent($this->faker->realText())
                ->setPublished($this->faker->dateTimeThisCentury)
                ->setAuthor($authorReference)
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
                $authorReference = $this->getRandomUserReference();

                $comment = new Comment();
                $comment->setContent($this->faker->realText())
                        ->setPublished($this->faker->dateTimeThisYear)
                        ->setAuthor($authorReference)
                        ->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username'])
                ->setEmail($userFixture['email'])
                ->setName($userFixture['name'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    $userFixture['password']
                ));

            $this->addReference('user_' . $userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    protected function getRandomUserReference(): User
    {
        return $this->getReference('user_' . self::USERS[rand(0,3)]['username']);
    }
}
