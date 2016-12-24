<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * @Config\Route("/groups/create/{project_id}", name="group-create")
     * @Config\Method({"GET"})
     * @return Response
     */
    public function createAction()
    {
        return $this->render('');
    }

    /**
     * @Config\Route("/groups/create/{project_id}")
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
     * @Config\Route("/tasks/{id}")
     * @Config\Method({"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteAction($id)
    {
    }
}