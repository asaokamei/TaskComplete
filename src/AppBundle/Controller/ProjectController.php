<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $project = new Project();
        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class)
            ->add('done_by', DateType::class)
            ->getForm();
        return $this->render('task/project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Config\Route("/projects/create", name="project-create")
     * @Config\Method({"POST"})
     * @return Response
     */
    public function insertAction()
    {
        $project = new Project();
        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class)
            ->add('done_by', DateType::class)
            ->getForm();
        return $this->render('task/project/create.html.twig', [
            'form' => $form->createView(),
        ]);
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
}
