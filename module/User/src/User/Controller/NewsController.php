<?php

namespace User\Controller;

use Base\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Model\JsonModel;

class NewsController extends AbstractRestfulController
{
    /**
     * NewsController constructor.
     * @param ServiceLocatorInterface $sl
     */
    public function __construct(ServiceLocatorInterface $sl)
    {
        $this->setServiceLocator($sl);
        $this->setEm($sl->get('Doctrine\ORM\EntityManager'));
        $this->setEntity("User\\Entity\\News");
        $this->setService("User\\Service\\NewsService");
    }

    public function getList()
    {
        try {
            $params = $this->params()->fromQuery();

            if (count($params) > 0)
            {
                if (array_key_exists('user', $params))
                {
                    $user = $this->getEm()->getReference('User\Entity\User', $params['user']);

                    $list = [];
                    foreach ($user->getFriends() as $friend)
                    {
                        foreach ($friend->getNews() as $news)
                            $list[] = $news;
                    }
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
