<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Test\FormInterface;
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
    public function createAction($project_id, $group_id)
    {
        $project = $this->get('app.project-crud')->findById($project_id);
        $group   = $this->get('app.group-crud')->findById($group_id);

        $form = $this->get('app.task-crud')->getCreateForm();
        return $this->render('task/task/create.html.twig', [
            'form'    => $form->createView(),
            'project' => $project,
            'group'   => $group,
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
        $group   = $this->get('app.group-crud')->findById($group_id);
        $crud    = $this->get('app.task-crud');

        /** @var FormInterface $form */
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
}