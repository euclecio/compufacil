<?php

namespace User\Service;

use Base\Service\AbstractService;
use Doctrine\ORM\EntityManager;
use Zend\Hydrator;

class NewsService extends AbstractService
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->entity = "User\\Entity\\News";
    }

    public function persist(array $data, $id = null)
    {
        $data['user'] = $this->em->getReference("User\\Entity\\User", $data['user']);

        return parent::persist($data, $id);
    }
}