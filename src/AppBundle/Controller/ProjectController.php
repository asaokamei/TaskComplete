<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * @Route("/projects/create", name="project-create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('task/project/create.html.twig');
    }

    /**
     * @Route("/projects/{id}", name="project-detail")
     * @return Response
     */
    public function showAction($id)
    {
        return $this->render('task/project/show.html.twig', [
            'project_id' => $id,
        ]);
    }
}
