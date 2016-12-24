<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @Config\Route("/projects/create", name="project-create-do")
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
                'error' => 'please check the input values!',
            ]);
        }
        $id = $this->createNewProject($form->getData());
        $this->addFlash('message', 'created a new project!');
        return $this->redirectToRoute('project-detail', ['id' => $id]);
    }

    /**
     * @Config\Route("/projects/{id}", name="project-detail")
     * @param int $id
     * @return Response
     */
    public function showAction($id)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();
        $projectRepo = $em->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $id]);
        return $this->render('task/project/show.html.twig', [
            'project_id' => $id,
            'project' => $project,
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
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
     * @param array $data
     * @return int
     */
    private function createNewProject(array $data)
    {
        return 1;
    }
}
