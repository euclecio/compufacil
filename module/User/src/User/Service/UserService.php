<?php

namespace User\Service;

use Base\Service\AbstractService;
use Doctrine\ORM\EntityManager;
use Zend\Hydrator;

class UserService extends AbstractService
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->entity = "User\\Entity\\User";
    }

    public function persist(array $data, $id = null)
    {
        if(empty($data['password']))
            unset($data['password']);

        if ($id)
        {
            $entity = $this->em->getReference($this->entity, $id);

            $hydrator = new Hydrator\ClassMethods();
            $hydrator->hydrate($data, $entity);
        }
        else
            $entity = new $this->entity($data);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
}
