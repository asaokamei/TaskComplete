<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('task/index.html.twig');
    }

    /**
     * @Route("/date", name="by-date")
     * @param Request $request
     * @return Response
     */
    public function dateAction(Request $request)
    {
        return $this->render('task/date.html.twig');
    }
}
