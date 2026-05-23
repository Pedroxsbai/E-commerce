<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function registerUser(User $user, string $plainPassword): void
    {
        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);

        // Ensure roles are set correctly (ROLE_USER is default but let's be explicit if needed)
        $user->setRoles(['ROLE_USER']);

        // Persist and flush
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
