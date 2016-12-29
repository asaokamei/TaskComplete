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
     * @Config\Route("/tasks/create/{project_id}/{group_id}", name="task-create")
     * @Config\Method({"GET"})
     * @param int $project_id
     * @param int $group_id
     * @return Response
     */
    public function createAction($project_id, $group_id)
    {
        $project = $this->get('app.project-crud')->findById($project_id);
        $group  = $this->get('app.group-crud')->findById($group_id);

        $form = $this->get('app.task-crud')->getCreateForm();
        return $this->render('task/task/create.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'group' => $group,
        ]);
    }

    /**
     * @Config\Route("/tasks/create/{project_id}/{group_id}")
     * @Config\Method({"POST"})
     * @param Request $request
     * @param int     $group_id
     * @param int     $project_id
     * @return Response
     */
    public function insertAction(Request $request, $project_id, $group_id)
    {
        $project = $this->get('app.project-crud')->findById($project_id);
        $group  = $this->get('app.group-crud')->findById($group_id);
        $crud = $this->get('app.task-crud');

        $form = $crud->getCreateForm();
        $form = $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render('task/task/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        $crud->create($project, $group, $form->getData());
        
        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
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
        
        $group = $task->getGroup();
        $project = $group->getProject();

        return $this->render('task/task/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'group' => $group,
            'task' => $task,
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

        $form = $form->handleRequest($request);
        $group = $task->getGroup();
        $project = $group->getProject();

        if (!$form->isValid()) {

            return $this->render('task/task/edit.html.twig', [
                'form' => $form->createView(),
                'project' => $project,
                'group' => $group,
            ]);
        }
        $crud->update($task, $form->getData());

        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
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
