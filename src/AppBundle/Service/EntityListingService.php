<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class EntityListingService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listEntities($namespace)
    {
        $entities = array();
        $meta = $this->em->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            if (strpos($m->getName(), $namespace) === 0) {
                $entities[explode($namespace, $m->getName())[1]] = $m->getName();
            }
        }

       return $entities;
    }
}