<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPersister implements DataPersisterInterface{

    private EntityManagerInterface $managerInterface;
    private UserPasswordEncoderInterface $encodePassword;

    public function __construct(EntityManagerInterface $managerInterface,UserPasswordEncoderInterface $encodePassword)
    {
        $this->managerInterface=$managerInterface;
        $this->encodePassword=$encodePassword;
    }
    /**
     * Is the data supported by the persister?
     */
    public function supports($data): bool 
    {
        return $data instanceof User;
    }

    /**
     * Persists the data.
     * 
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data):void
    {
        if ($data->getPassword()) {
            $data->setPassword($this->encodePassword->encodePassword(new User,$data->getPassword()));
        }
        $this->managerInterface->persist($data);
        $this->managerInterface->flush();
    }

    /**
     * Removes the data.
     */
    public function remove($data):void
    {
        $this->managerInterface->remove($data);
        $this->managerInterface->flush();
    }

}