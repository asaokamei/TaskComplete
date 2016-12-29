<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        $summary = $this->get('app.query-task-summary')->getSummary();
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();
        $projectRepo = $em->getRepository(Project::class);
        $projects = $projectRepo->findAll();
        return $this->render('task/index.html.twig', [
            'projects' => $projects,
            'summary' => $summary,
        ]);
    }

    /**
     * @Route("/projects", name="by-project")
     * @return Response
     */
    public function projectAction()
    {
        $projects = $this->get('app.query-by-project')->getProjects();
        $taskJS = $this->get('app.task-crud')->getDoneActivateJS();
        return $this->render('task/project.html.twig', [
            'projects' => $projects,
            'taskJS' => $taskJS,
        ]);
    }

    /**
     * @Route("/date", name="by-date")
     * @return Response
     */
    public function dateAction()
    {
        return $this->render('task/date.html.twig');
    }
}
