<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * @Route("/projects/create", name="project-create")
     * @return Response
     */
    public function createAction()
    {
        return $this->render('task/project/create.html.twig');
    }

    /**
     * @Route("/projects/{id}", name="project-detail")
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
