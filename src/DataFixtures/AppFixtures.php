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
            'password' => 'Secret123',
            'roles' => [User::ROLE_SUPERADMIN]
        ],
        [
            'username' => 'faisaluje',
            'email' => 'faisaluje@blog.co',
            'name' => 'Faisal Uje',
            'password' => 'Secret123',
            'roles' => [User::ROLE_ADMIN]
        ],
        [
            'username' => 'ahmad',
            'email' => 'ahmad@blog.co',
            'name' => 'Ahmad Apandi',
            'password' => 'Secret123',
            'roles' => [User::ROLE_WRITER]
        ],
        [
            'username' => 'banu',
            'email' => 'banu@blog.co',
            'name' => 'Banu Supriyadi',
            'password' => 'Secret123',
            'roles' => [User::ROLE_WRITER]
        ],
        [
            'username' => 'yadi',
            'email' => 'yadi@blog.co',
            'name' => 'Yadi MR',
            'password' => 'Secret123',
            'roles' => [User::ROLE_EDITOR]
        ],
        [
            'username' => 'dedi',
            'email' => 'dedi@blog.co',
            'name' => 'Dedi Suryadi',
            'password' => 'Secret123',
            'roles' => [User::ROLE_COMMENTATOR]
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
            $blogPost = new BlogPost();

            $authorReference = $this->getRandomUserReference($blogPost);
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
        for ($i=0;$i<100;$i++) {
            for ($j=0;$j<rand(1, 10);$j++) {
                $comment = new Comment();

                $authorReference = $this->getRandomUserReference($comment);
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
                ))
                ->setRoles($userFixture['roles']);
            $user->setEnabled(true);

            $this->addReference('user_' . $userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    protected function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0, 5)];

        if ($entity instanceof BlogPost && !count(
                array_intersect(
                    $randomUser['roles'],
                    [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER])
            )) {
            return $this->getRandomUserReference($entity);
        }

        if ($entity instanceof Comment && !count(
                array_intersect(
                    $randomUser['roles'],
                    [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER, User::ROLE_COMMENTATOR])
            )) {
            return $this->getRandomUserReference($entity);
        }

        return $this->getReference('user_' . $randomUser['username']);
    }
}
