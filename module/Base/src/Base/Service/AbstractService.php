<?php

/**
* Class AbstractService
*
* @author Euclécio Josias Rodrigues <https://github.com/euclecio>
* @version 1.0
*
* Require Doctrine (https://github.com/doctrine/DoctrineORMModule.git)
*/

namespace Base\Service;

use Doctrine\ORM\EntityManager;
use Zend\Hydrator;

abstract class AbstractService
{
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var string $entity
     */
    protected $entity;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function persist(array $data, $id = null)
    {
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

    public function delete($id)
    {
        $entity = $this->em->getReference($this->entity, $id);
        if($entity)
        {
            $this->em->remove($entity);
            $this->em->flush();
            return $id;
        }
    }
}
