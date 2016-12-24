<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
use AppBundle\Entity\Tasks\TaskStatus;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemController extends Controller
{
    /**
     * @Route("/initialize", name="initialize")
     * @Method({"GET"})
     * @return Response
     */
    public function askInitializeAction()
    {
        return $this->render('task/system/initialize.html.twig');
    }

    /**
     * @Route("/initialize")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function initializeDbAction(Request $request)
    {
        if ($request->get('action') !== 'initialize') {
            $this->addFlash('notice', 'please check to initialize database.');
            return $this->redirectToRoute('initialize');
        }
        if (!$em = $this->getDoctrine()->getManager()) {
            $this->addFlash('notice', 'entity manager for doctrine not found.');
            return $this->redirectToRoute('initialize');
        }

        $this->cleanupDb($em);
        $this->populateDb($em);
        
        $this->addFlash(
            'message',
            'filled up initial tasks!'
        );
        return $this->redirectToRoute('initialize');
    }

    /**
     * @param ObjectManager|EntityManager $em
     */
    private function cleanupDb($em)
    {
        $em->createQuery('delete AppBundle\Entity\Tasks\Task t')->execute();
        $em->createQuery('delete AppBundle\Entity\Tasks\Group g')->execute();
        $em->createQuery('delete AppBundle\Entity\Tasks\Project p')->execute();
    }

    /**
     * @param ObjectManager $em
     */
    private function populateDb($em)
    {
        $now = new \DateTime('now');
        
        // create 2 projects: work and life. 
        $work = new Project(['name' => 'work']);
        $em->persist($work);

        $life = new Project(['name' => 'life']);
        $em->persist($life);
        
        // create basic groups for work and life.
        $group1 = new Group(['name' => 'tasks']);
        $group1->setProject($work);
        $em->persist($group1);

        $group2 = new Group(['name' => 'plans']);
        $group2->setProject($life);
        $em->persist($group2);
        
        // add tasks for work/tasks
        $task1 = new Task([
            'status' => TaskStatus::DONE,
            'title' => 'brain storm', 
            'doneBy' => (clone $now)->sub(new \DateInterval('P1D')),
            'doneAt' => (clone $now),
        ]);
        $task1->setGroup($group1);
        $em->persist($task1);

        $task1 = new Task(['title' => 'write proposal', 'doneBy' => (clone $now)->add(new \DateInterval('P1D'))]);
        $task1->setGroup($group1);
        $em->persist($task1);

        $task2 = new Task(['title' => 'develop product', 'doneBy' => (clone $now)->add(new \DateInterval('P2D'))]);
        $task2->setGroup($group1);
        $em->persist($task2);
        
        // add tasks for life/plans
        $task3 = new Task(['title' => 'go camping']);
        $task3->setGroup($group2);
        $em->persist($task3);

        $task4 = new Task(['title' => 'go to beach']);
        $task4->setGroup($group2);
        $em->persist($task4);

        // save to database. 
        $em->flush();
    }    
}