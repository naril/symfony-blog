<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager
{
    private $em;
    private $encoder;
    private $validator;

    public function __construct(EntityManagerInterface $em, EncoderFactoryInterface $encoder, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    public function addUser($username, $password, $name = null)
    {
        $user = new User();
        $encoder = $this->encoder->getEncoder($user);

        if ($name) {
            $user->setName($name);
        }
        $user->setUsername($username);

        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new InvalidArgumentException($errors);
        }

        $this->em->persist($user);
        $this->em->flush($user);

        return $user;
    }

    public function updateUser($id, $params)
    {
        /**
         * @var User $user
         */
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
        if (!$user) {
            throw new EntityNotFoundException($id);
        }

        if (!empty($params["name"]) && $user->getName() !== $params["name"]) {
            $user->setName($params["name"]);
        }
        if (!empty($params["username"]) && $user->getUsername() !== $params["username"]) {
            $user->setUsername($params["username"]);
        }

        if (!empty($params["password"]) && $user->getPassword() !== $params["password"]) {
            $encoder = $this->encoder->getEncoder($user);
            $user->setPassword($encoder->encodePassword($params["password"], $user->getSalt()));
        }

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new InvalidArgumentException($errors);
        }

        $this->em->persist($user);
        $this->em->flush($user);

        return $user;
    }

    public function removeUser($id)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
        if (!$user) {
            throw new EntityNotFoundException($id);
        }

        $this->em->remove($user);
        $this->em->flush();
    }
}