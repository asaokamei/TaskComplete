<?php
namespace AppBundle\Controller;

use AppBundle\Controller\CrudService\GroupCrud;
use AppBundle\Controller\CrudService\ProjectCrud;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * @Config\Route("/projects/create", name="project-create")
     * @Config\Method({"GET"})
     * @return Response
     */
    public function createAction()
    {
        $crud = $this->get('app.project-crud');
        $form    = $crud->getCreateForm();

        return $this->render('task/project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Config\Route("/projects/create")
     * @param Request $request
     * @return Response
     */
    public function insertAction(Request $request)
    {
        $crud = $this->get('app.project-crud');
        $form = $crud->getCreateForm();
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render('task/project/create.html.twig', [
                'form' => $form->createView(),
                'notice' => 'please check the input values!',
            ]);
        }
        $id = $crud->create($form->getData());
        $this->addFlash('message', 'created a new project!');
        return $this->redirectToRoute('project-detail', ['id' => $id]);
    }

    /**
     * @Config\Route("/projects/{id}", name="project-detail")
     * @Config\Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        /** @var ProjectCrud $crud */
        $crud = $this->get('app.project-crud');
        $project = $crud->findById($id);
        $updater = $crud->getUpdateForm($project->toArray());

        /** @var GroupCrud $gCrud */
        $gCrud = $this->get('app.group-crud');
        $gForm = $gCrud->getCreateForm($id);
        $taskJs = $this->get('app.task-crud')->getDoneActivateJS();
        
        return $this->render('task/project/show.html.twig', [
            'project_id' => $id,
            'project' => $project,
            'updater' => $updater->createView(),
            'newGroup' => $gForm->createView(),
            'taskJS' => $taskJs,
        ]);
    }

    /**
     * @Config\Route("/projects/{id}")
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $crud = $this->get('app.project-crud');
        $form = $crud->getUpdateForm();
        $form->handleRequest($request);
        if (!$form->isValid()) {
            $this->addFlash('notice', 'bad input: not updated');
            return $this->redirectToRoute('project-detail', ['id' => $id]);
        }

        $crud->update($id, $form->getData());

        $this->addFlash('message', 'updated project information!');
        return $this->redirectToRoute('project-detail', ['id' => $id]);
    }

    /**
     * @Config\Route("/projects/{id}")
     * @Config\Method({"DELETE"})
     * @param int $id
     * @return Response
     */
    public function closeAction($id)
    {
        // TODO: implement this method
    }

    /**
     * @return Response
     */
    public function listActiveProjectsAction()
    {
        $projects = $this->get('app.query-list-projects')->getList();
        
        return $this->render('task/includes/navbar-projects.html.twig', [
            'projects' => $projects,
        ]);
    }
}
