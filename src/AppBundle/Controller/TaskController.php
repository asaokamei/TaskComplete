<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * @Config\Route("/tasks/create/{project_id}/{group_id}", name="task-create")
     * @return Response
     */
    public function createAction()
    {
        return $this->render('task/task/create.html.twig');
    }

    /**
     * @Config\Route("/tasks/create/{project_id}/{group_id}")
     * @Config\Method({"POST"})
     * @param Request $request
     * @param int     $group_id
     * @param int     $project_id
     * @return Response
     */
    public function insertAction(Request $request, $group_id, $project_id)
    {
    }

    /**
     * @Config\Route("/tasks/{id}", name="task-detail")
     * @Config\Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        return $this->render('task/task/show.html.twig', [
            'project_id' => $id,
        ]);
    }

    /**
     * @Config\Route("/tasks/{id}")
     * @Config\Method({"POST"})
     * @param int $id
     * @return Response
     */
    public function updateAction($id)
    {
        return $this->render('task/task/show.html.twig', [
            'project_id' => $id,
        ]);
    }

    /**
     * @Config\Route("/tasks/{id}/done")
     * @Config\Method({"POST"})
     * @param int $id
     * @return Response
     */
    public function doneAction($id)
    {
    }

    /**
     * @Config\Route("/tasks/{id}/activate")
     * @Config\Method({"POST"})
     * @param int $id
     * @return Response
     */
    public function activateAction($id)
    {
    }

    /**
     * @Config\Route("/tasks/{id}")
     * @Config\Method({"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteAction($id)
    {
    }
}
