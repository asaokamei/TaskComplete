<?php
namespace AppBundle\AppService\Common;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class Transaction implements ServiceInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ServiceInterface
     */
    private $service;

    /**
     * Transaction constructor.
     *
     * @param EntityManager $em
     * @param ServiceInterface $service
     */
    public function __construct($em, $service)
    {
        $this->em = $em;
        $this->service = $service;
    }

    /**
     * @param Request    $request
     * @return ServiceDTO
     * @throws \Exception
     */
    public function run($request)
    {
        try {
            
            $this->em->beginTransaction();
            $dto = $this->service->run($request);
            if (!$dto instanceof ServiceDTO) {
                return $dto;
            }
            if (!$dto->isValid()) {
                $this->em->rollback();
            } else {
                $this->em->flush();
                $this->em->commit();
            }
            return $dto;

        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}