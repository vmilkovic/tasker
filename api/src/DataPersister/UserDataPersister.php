<?php

namespace App\DataPersister;

use App\Entity\User;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements DataPersisterInterface
{
    #TODO setup Mailer https://api-platform.com/docs/core/data-persisters/

    private $decorated;
    private $passwordHasher;

    public function __construct(DataPersisterInterface $decorated, UserPasswordHasherInterface $passwordHasher)
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
        #TODO user exist check

        if ($data instanceof User && ($context['collection_operation_name'] ?? null) === 'CREATE_USER') {
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
