<?php
namespace AppBundle\Controller;

use AppBundle\Controller\CrudService\TaskCrud;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * @Config\Route("/tasks/{id}", name="task-detail")
     * @Config\Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        throw new \InvalidArgumentException('task view not supported yet. id = ' . $id);
    }

    /**
     * done a task.
     * accessed via various pages using Ajax.
     *
     * @Config\Route("/tasks/{id}/done", name="task-done")
     * @Config\Method({"POST"})
     * @param int $id
     * @return Response
     */
    public function doneAction($id)
    {
        /** @var TaskCrud $crud */
        $crud = $this->get('app.task-crud');
        $crud->done($id);
        return Response::create('', 200);
    }

    /**
     * activate a task (probably re-activate a done task).
     * accessed via various pages using Ajax.
     *
     * @Config\Route("/tasks/{id}/activate", name="task-activate")
     * @Config\Method({"POST"})
     * @param int $id
     * @return Response
     */
    public function activateAction($id)
    {
        /** @var TaskCrud $crud */
        $crud = $this->get('app.task-crud');
        $crud->activate($id);
        return Response::create('', 200);
    }
}
