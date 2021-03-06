<?php
namespace AppBundle\Controller;

use AppBundle\AppService\GroupCrud\GroupCrud;
use AppBundle\AppService\ProjectCrud\ProjectCrud;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProjectController
 *
 * @package AppBundle\Controller
 * @Config\Route("/projects/")
 */
class ProjectController extends Controller
{
    /**
     * @Config\Route("create", name="project-create")
     * @Config\Method({"GET"})
     * @return Response
     */
    public function createAction()
    {
        $crud = $this->get('app.project-crud');
        $form = $crud->getCreateForm();

        return $this->viewCreateForm($form);
    }

    /**
     * @Config\Route("create")
     * @param Request $request
     * @return Response
     */
    public function insertAction(Request $request)
    {
        $crud = $this->get('app.project-crud');
        /** @var FormInterface $form */
        $dto = $crud->create($request);
        if (!$dto->isValid()) {
            $this->addFlash('notice', $dto->getMessage());
            return $this->viewCreateForm($dto->getForm());
        }

        $this->addFlash('message', 'created a new project!');

        return $this->redirectToRoute('project-detail', ['id' => $dto->getCreatedId()]);
    }

    /**
     * @Config\Route("{id}", name="project-detail", requirements={"id": "\d+"})
     * @Config\Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        /** @var ProjectCrud $crud */
        $crud    = $this->get('app.project-crud');
        $project = $crud->findById($id);
        $updater = $crud->getUpdateForm($project);

        /** @var GroupCrud $gCrud */
        $gCrud  = $this->get('app.group-crud');
        $gForm  = $gCrud->getCreateForm();
        $taskJs = $this->get('app.task-crud')->getDoneActivateJS();

        return $this->render('task/project/show.html.twig', [
            'project_id' => $id,
            'project'    => $project,
            'updater'    => $updater->createView(),
            'newGroup'   => $gForm->createView(),
            'taskJS'     => $taskJs,
        ]);
    }

    /**
     * @Config\Route("{id}", requirements={"id": "\d+"})
     * @Config\Method({"POST"})
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $crud    = $this->get('app.project-crud');
        $project = $crud->findById($id);

        $form = $crud->update($project, $request);
        if (!$form->isValid()) {
            $this->addFlash('notice', 'bad input: not updated');

            return $this->redirectToRoute('project-detail', ['id' => $id]);
        }

        $this->addFlash('message', 'updated project information!');

        return $this->redirectToRoute('project-detail', ['id' => $id]);
    }

    /**
     * @Config\Route("{id}", requirements={"id": "\d+"})
     * @Config\Method({"DELETE"})
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function closeAction(Request $request, $id)
    {
        /** @var ProjectCrud $crud */
        $crud           = $this->get('app.project-crud');
        $project        = $crud->findById($id);
        $submittedToken = $request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('token_id', $submittedToken)) {
            $this->addFlash('notice', 'cannot delete task: no valid token.');

            return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
        }
        if ($request->get('action') !== 'delete') {
            $this->addFlash('notice', 'please check to delete this task. ');

            return $this->redirectToRoute('project-detail', ['id' => $project->getId()]);
        }
        $crud->delete($project);

        return $this->redirectToRoute('homepage');
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

    /**
     * @param FormInterface $form
     * @return Response
     */
    private function viewCreateForm($form): Response
    {
        return $this->render('task/project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
