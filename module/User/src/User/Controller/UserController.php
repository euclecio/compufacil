<?php

namespace User\Controller;

use Base\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Model\JsonModel;

class UserController extends AbstractRestfulController
{
    /**
     * UserController constructor.
     * @param ServiceLocatorInterface $sl
     */
    public function __construct(ServiceLocatorInterface $sl)
    {
        $this->setServiceLocator($sl);
        $this->setEm($sl->get('Doctrine\ORM\EntityManager'));
        $this->setEntity("User\\Entity\\User");
        $this->setService("User\\Service\\UserService");
    }

    public function getList()
    {
        try {
            $params = $this->params()->fromQuery();

            if (count($params) > 0)
            {
                if (array_key_exists('byUser', $params))
                {
                    $user = $this->getEm()->getReference($this->getEntity(), $params['byUser']);
                    $list = $user->getFriends();
                }
                else
                    $list = $this->getEm()->getRepository($this->getEntity())->findBy($params);
            }
            else
                $list = $this->getEm()->getRepository($this->getEntity())->findAll();

            foreach ($list as $key => $entity)
                $list[$key] = $entity->toArray();

            return new JsonModel($list);
        } catch (\Exception $e) {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => $e->getMessage(),
            ));
        }
    }
}
