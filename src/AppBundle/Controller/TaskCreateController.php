<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskCreateController extends Controller
{
    /**
     * @Config\Route("/tasks/create/{project_id}/{group_id}", name="task-create")
     * @Config\Method({"GET"})
     * @param int $project_id
     * @param int $group_id
     * @return Response
     */
    public function createAction(int $project_id, int $group_id): Response
    {
        $form = $this->get('app.task-create')->getCreateForm();

        return $this->makeCreateView($project_id, $group_id, $form);
    }

    /**
     * @Config\Route("/tasks/create/{project_id}/{group_id}")
     * @Config\Method({"POST"})
     * @param Request $request
     * @param int     $group_id
     * @param int     $project_id
     * @return Response
     */
    public function insertAction(Request $request, int $project_id, int $group_id): Response
    {
        $project = $this->get('app.project-crud')->findById($project_id);
        $group   = $this->get('app.group-crud')->findById($group_id);
        $crud    = $this->get('app.task-create');

        /** @var FormInterface $form */
        $form = $crud->create($project, $group, $request);
        if (!$form->isValid()) {
            $form->addError(new FormError('please check inputs. '));

            return $this->makeCreateView($project_id, $group_id, $form);
        }

        $this->addFlash('message', 'created a new task. ');

        return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
    }

    /**
     * @param int                $project_id
     * @param int                $group_id
     * @param FormInterface|null $form
     * @return Response
     */
    private function makeCreateView(int $project_id, int $group_id, FormInterface $form): Response
    {
        $project = $this->get('app.project-crud')->findById($project_id);
        $group   = $this->get('app.group-crud')->findById($group_id);

        return $this->render('task/task/create.html.twig', [
            'form'    => $form->createView(),
            'project' => $project,
            'group'   => $group,
        ]);
    }
}