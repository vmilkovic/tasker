<?php

namespace App\DataPersister;

use App\Entity\User;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    #TODO setup Mailer https://api-platform.com/docs/core/data-persisters/

    private $decorated;
    private $passwordHasher;

    public function __construct(ContextAwareDataPersisterInterface $decorated, UserPasswordHasherInterface $passwordHasher)
    {
        $this->decorated = $decorated;
        $this->passwordHasher = $passwordHasher;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {

        if ($data instanceof User && ($context['collection_operation_name'] ?? null) === 'post') {
            $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPassword()));
        }

        $result = $this->decorated->persist($data, $context);

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}
