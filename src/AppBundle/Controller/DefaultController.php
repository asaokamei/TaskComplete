<?php
namespace AppBundle\Controller;

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

        return $this->render('task/index.html.twig', [
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
        $taskJS   = $this->get('app.task-crud')->getDoneActivateJS();

        return $this->render('task/project.html.twig', [
            'projects' => $projects,
            'taskJS'   => $taskJS,
        ]);
    }

    /**
     * @Route("/dates", name="by-date")
     * @return Response
     */
    public function dateAction()
    {
        $dateType = $this->get('app.query-by-date')->getTasks();
        $taskJS   = $this->get('app.task-crud')->getDoneActivateJS();

        return $this->render('task/date.html.twig', [
            'dateType' => $dateType,
            'taskJS'   => $taskJS,
        ]);
    }

    /**
     * @Route("/targets", name="by-target")
     * @return Response
     */
    public function targetAction()
    {
        $groups = $this->get('app.query-by-target')->getGroups();
        $taskJS = $this->get('app.task-crud')->getDoneActivateJS();

        return $this->render('task/target.html.twig', [
            'groups' => $groups,
            'taskJS' => $taskJS,
        ]);
    }
}
