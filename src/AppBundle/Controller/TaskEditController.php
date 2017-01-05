<?php
namespace AppBundle\Controller;

use AppBundle\Controller\CrudService\TaskCrud;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskEditController extends Controller
{
    /**
     * @Config\Route("/tasks/{id}/edit", name="task-edit")
     * @Config\Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function editAction($id)
    {
        $crud = $this->get('app.task-crud');
        $task = $crud->findById($id);
        $form = $crud->getUpdateForm($task->toArray());

        $taskJS = $crud->getDoneActivateJS();

        return $this->render('task/task/edit.html.twig', [
            'form'   => $form->createView(),
            'task'   => $task,
            'taskJS' => $taskJS,
        ]);
    }

    /**
     * @Config\Route("/tasks/{id}/edit")
     * @Config\Method({"POST"})
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        /** @var TaskCrud $crud */
        $crud = $this->get('app.task-crud');
        $task = $crud->findById($id);
        $form = $crud->getUpdateForm($task->toArray());

        /** @var FormInterface $form */
        $form    = $form->handleRequest($request);
        $group   = $task->getGroup();
        $project = $group->getProject();

        if (!$form->isValid()) {

            return $this->render('task/task/edit.html.twig', [
                'form'    => $form->createView(),
                'project' => $project,
                'group'   => $group,
            ]);
        }
        $crud->update($task, $form->getData());

        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
    }
}