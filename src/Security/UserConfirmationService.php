<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 20/03/19
 * Time: 16:04
 */

namespace App\Security;

use App\Exception\InvalidConfirmationTokenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserConfirmationService
{
    private $userRepository;
    private $entityManager;
    private $logger;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function confirmUser(string $confirmationToken)
    {
        $this->logger->debug('Fetching user by confirmation token');

        $user = $this->userRepository->findOneBy(
            ['confirmationToken' => $confirmationToken]
        );

        // User was NOT found by confirmation token
        if (!$user) {
            $this->logger->debug('User by confirmation token not found');
            throw new InvalidConfirmationTokenException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();

        $this->logger->debug('Confirmed user by confirmation token');
    }
}