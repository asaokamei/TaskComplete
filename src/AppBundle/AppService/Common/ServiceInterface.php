<?php
namespace AppBundle\AppService\Common;

use Symfony\Component\HttpFoundation\Request;

interface ServiceInterface
{
    /**
     * @param Request    $request
     * @return ServiceDTO
     * @throws \Exception
     */
    public function run($request);
}