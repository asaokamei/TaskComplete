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
        return $this->render('task/index.html.twig');
    }

    /**
     * @Route("/projects", name="by-project")
     * @param Request $request
     * @return Response
     */
    public function projectAction(Request $request)
    {
        return $this->render('task/project.html.twig');
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
