<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
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
     * @param Request $request
     * @return Response
     */
    public function askInitializeAction(Request $request)
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
            'success',
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
        // create 2 projects: work and life. 
        $work = new Project();
        $work->setName('work');
        $em->persist($work);

        $life = new Project();
        $life->setName('life');
        $em->persist($life);
        
        // create basic groups for work and life.
        $group1 = new Group();
        $group1->setName('tasks');
        $group1->setProject($work);
        $em->persist($group1);

        $group2 = new Group();
        $group2->setName('plans');
        $group2->setProject($life);
        $em->persist($group2);
        
        // add tasks for work/tasks
        $task1 = new Task();
        $task1->setTitle('write proposal');
        $task1->setGroup($group1);
        $em->persist($task1);

        $task2 = new Task();
        $task2->setTitle('develop product');
        $task2->setGroup($group1);
        $em->persist($task2);
        
        // add tasks for life/plans
        $task3 = new Task();
        $task3->setTitle('go camping');
        $task3->setGroup($group2);
        $em->persist($task3);

        $task4 = new Task();
        $task4->setTitle('go to beach');
        $task4->setGroup($group2);
        $em->persist($task4);

        // save to database. 
        $em->flush();
    }    
}