<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * @Route("/tasks/create", name="task-create")
     * @return Response
     */
    public function createAction()
    {
        return $this->render('task/task/create.html.twig');
    }

    /**
     * @Route("/tasks/{id}", name="task-detail")
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        return $this->render('task/task/show.html.twig', [
            'project_id' => $id,
        ]);
    }
}
