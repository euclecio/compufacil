<?php

namespace Base\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController as RestfulController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\Hydrator;

abstract class AbstractRestfulController extends RestfulController 
{
    /**
     * @var ServiceLocatorInterface $serviceLocator
     */
    protected $serviceLocator;

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var string $entity
     */
    protected $entity;

    /**
     * @var string $service
     */
    protected $service;

	public function getList()
	{
        try {
            $params = $this->params()->fromQuery();

            if (count($params) > 0)
                $list = $this->getEm()->getRepository($this->getEntity())->findBy($params);
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

	public function get($id)
	{
        try {
            $entity = $this->getEm()
                           ->getRepository($this->getEntity())
                           ->findOneById($id);

            return new JsonModel($entity->toArray());
        } catch (\Exception $e) {
            $this->response->setStatusCode(405);

            return new JsonModel(array(
                'error' => $e->getMessage(),
            ));
        }
	}

    public function create($data)
    {
        try {
            $service = $this->getServiceLocator()->get($this->getService());
            $entity  = $service->persist($data);
            $this->response->setStatusCode(201);
            return new JsonModel($entity->toArray());
        } catch (\Exception $e) {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => $e->getMessage(),
            ));
        }
    }

    public function update($id, $data)
    {
        try {
            $service = $this->getServiceLocator()->get($this->getService());
            $entity  = $service->persist($data, $id);
            return new JsonModel($entity->toArray());
        } catch (\Exception $e) {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => $e->getMessage(),
            ));
        }
    }

    public function patch($id, $data)
    {
        try {
            $service = $this->getServiceLocator()->get($this->getService());
            $entity  = $service->persist($data, $id);
            return new JsonModel($entity->toArray());
        } catch (\Exception $e) {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => $e->getMessage(),
            ));
        }
    }

	public function delete($id)
	{
		try {
            $service = $this->getServiceLocator()->get($this->getService());
            $service->delete($id);
            $this->response->setStatusCode(204);
            return new JsonModel(array(
                'id'      => $id,
                'success' => true
            ));
        } catch (\Exception $e) {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
				'error' => $e->getMessage(),
			));
        }
	}

    /**
     * Set serviceManager instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param mixed $em
     * @return this
     */
    public function setEm($em)
    {
        $this->em = $em;

        return $this;
    }

	/**
	 *
	 * @return EntityManager
	 */
	public function getEm()
	{
		return $this->em;
	}

    /**
     * @param string $entity
     * @return this
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;

        return $this;
    }

	/**
	 * @return string
	 */
	public function getEntity()
	{
		return $this->entity;
	}

    /**
     * @param string $service
     * @return this
     */
    public function setService(string $service)
    {
        $this->service = $service;

        return $this;
    }

	/**
	 * @return string
	 */
	public function getService()
	{
		return $this->service;
	}
}