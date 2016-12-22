<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();
        $projectRepo = $em->getRepository(Project::class);
        $projects = $projectRepo->findAll();
        return $this->render('task/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/projects", name="by-project")
     * @param Request $request
     * @return Response
     */
    public function projectAction(Request $request)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();
        $projects = $em->getRepository(Project::class);
        $projects = $projects->findAll();
        return $this->render('task/project.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/date", name="by-date")
     * @param Request $request
     * @return Response
     */
    public function dateAction(Request $request)
    {
        return $this->render('task/date.html.twig');
    }
}
