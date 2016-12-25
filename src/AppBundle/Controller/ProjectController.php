<?php
namespace AppBundle\Controller;

use AppBundle\Controller\Project\ProjectCrud;
use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
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
        $form    = $this->getCreateForm();

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
        $form = $this->getCreateForm();
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render('task/project/create.html.twig', [
                'form' => $form->createView(),
                'notice' => 'please check the input values!',
            ]);
        }
        $id = $this->createNewProject($form->getData());
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
        $updater = $this->getUpdateForm();

        return $this->render('task/project/show.html.twig', [
            'project_id' => $id,
            'project' => $project,
            'updater' => $updater->createView(),
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
        $project = $this->getProjectById($id);
        
        $form = $this->getUpdateForm();
        $form->handleRequest($request);
        if (!$form->isValid()) {
            $this->addFlash('notice', 'bad input: not updated');
            return $this->redirectToRoute('project-detail', ['id' => $id]);
        }

        $this->updateProject($project, $form->getData());

        $this->addFlash('message', 'updated project information!');
        return $this->redirectToRoute('project-detail', ['id' => $id]);
    }

    /**
     * @return FormInterface
     */
    private function getCreateForm()
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, ['required' => true, 'label' => 'Project name'])
            ->add('done_by', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('group_name', TextType::class, ['required' => true])
            ->getForm();

        return $form;
    }

    /**
     * @return FormInterface
     */
    private function getUpdateForm()
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, ['required' => true,])
            ->add('done_by', DateType::class, ['required' => false, 'widget' => 'single_text'])
            ->getForm();

        return $form;
    }

    /**
     * @param array $data
     * @return int
     */
    private function createNewProject(array $data)
    {
        $group_data = ['name' => $data['group_name']];
        unset($data['group_name']);
        
        $project = new Project($data);
        $group   = new Group($group_data);
        $group->setProject($project);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->persist($group);
        $em->flush();
        
        return $project->getId();
    }

    /**
     * @param int $id
     * @return Project|null|object
     */
    private function getProjectById($id)
    {
        /** @var EntityManager $em */
        $em          = $this->getDoctrine()->getManager();
        $projectRepo = $em->getRepository(Project::class);
        $project     = $projectRepo->findOneBy(['id' => $id]);

        return $project;
    }

    /**
     * @param Project $project
     * @param array $data
     */
    private function updateProject($project, $data)
    {
        $project->fill($data);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }

    /**
     * @Config\Route("/projects/{id}")
     * @Config\Method({"DELETE"})
     * @param int $id
     * @return Response
     */
    public function closeAction($id)
    {
    }
}
