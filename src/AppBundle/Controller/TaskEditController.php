<?php
namespace AppBundle\Controller;

use AppBundle\Controller\CrudService\TaskUpdate;
use AppBundle\Entity\Tasks\Task;
use InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskEditController extends Controller
{
    /**
     * @var TaskUpdate
     */
    private $crud;

    /**
     * @Config\Route("/tasks/{id}/edit", name="task-edit")
     * @Config\Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function editAction($id)
    {
        $task = $this->getTask($id);
        return $this->renderEdit($task);
    }

    /**
     * call this method first.
     * gets Task entity and sets TaskCrud for later use.
     *
     * @param int $id
     * @return Task
     */
    private function getTask($id)
    {
        $this->crud = $this->get('app.task-update');
        $task  = $this->crud->findById($id);
        if (!$task) {
            throw new InvalidArgumentException('no such task id: '.(int) $id);
        }
        return $task;
    }

    /**
     * @param Task          $task
     * @param FormInterface $form
     * @return Response
     */
    private function renderEdit(Task $task, FormInterface $form = null): Response
    {
        $form = $form ?: $this->crud->getUpdateForm($task);

        return $this->render('task/task/edit.html.twig', [
            'form'   => $form->createView(),
            'task'   => $task,
            'taskJS' => $this->crud->getDoneActivateJS(),
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
        $task = $this->getTask($id);

        /** @var FormInterface $form */
        $form = $this->crud->update($task, $request);
        if (!$form->isValid()) {
            $this->addFlash('notice', 'please check inputs. ');
            return $this->renderEdit($task, $form);
        }

        $group   = $task->getGroup();
        $project = $group->getProject();
        $this->addFlash('message', 'updated a task. ');
        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
    }

    /**
     * completely deletes a task from database.
     *
     * @Config\Route("/tasks/{id}/edit")
     * @Config\Method({"DELETE"})
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var TaskUpdate $crud */
        $crud = $this->get('app.task-update');
        $task = $crud->findById($id);
        $submittedToken = $request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('token_id', $submittedToken)) {
            $this->addFlash('notice', 'cannot delete task: no valid token.');
            return $this->redirectToRoute('task-edit', ['id' => $task->getId()]);
        }
        if ($request->get('action') !== 'delete') {
            $this->addFlash('notice', 'please check to delete this task. ');
            return $this->redirectToRoute('task-edit', ['id' => $task->getId()]);
        }
        $crud->delete($task);

        $group = $task->getGroup();
        $project = $group->getProject();
        $this->addFlash('message', 'deleted a task!');
        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
    }
}