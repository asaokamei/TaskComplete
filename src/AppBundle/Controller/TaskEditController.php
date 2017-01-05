<?php
namespace AppBundle\Controller;

use AppBundle\Controller\CrudService\TaskCrud;
use AppBundle\Entity\Tasks\Task;
use InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskEditController extends Controller
{
    /**
     * @var TaskCrud
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
        $this->crud = $this->get('app.task-crud');
        $task  = $this->crud->findById($id);
        if (!$task) {
            throw new InvalidArgumentException('no such task id: '.(int) $id);
        }
        return $task;
    }

    /**
     * @param Task  $task
     * @param array $data
     * @return Response
     */
    private function renderEdit(Task $task, array $data = []): Response
    {
        $data = array_merge($task->toArray(), $data);
        $form = $this->crud->getUpdateForm($data);

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
            return $this->renderEdit($task, $form->getData());
        }

        $group   = $task->getGroup();
        $project = $group->getProject();
        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
    }
}