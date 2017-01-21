<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class SystemController extends Controller
{
    /**
     * @var DateTime
     */
    private $now;

    /**
     * @Config\Route("/settings/login", name="settings-login")
     * @Config\Method({"GET", "POST"})
     * @return Response
     */
    public function login()
    {
        $user = $this->getUser();
        if ($user instanceof UserInterface) {
            return $this->redirectToRoute('homepage');
        }

        /** @var AuthenticationException $exception */
        $utils = $this->get('security.authentication_utils');
        $exception = $utils->getLastAuthenticationError();
        $lastName  = $utils->getLastUsername();

        $data = [
            'lastName' => $lastName,
        ];
        if ($exception) {
            $this->addFlash('notice', $exception->getMessage());
        } else {
            $this->addFlash('message', 'please login');
        }
        return $this->render('task/system/login.html.twig', $data);
    }
    
    /**
     * @Config\Route("/settings/initialize", name="initialize")
     * @Config\Method({"GET"})
     * @return Response
     */
    public function askInitializeAction()
    {
        return $this->render('task/system/initialize.html.twig');
    }

    /**
     * @Config\Route("/initialize")
     * @Config\Method({"POST"})
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
     * @param string|null $by
     * @return DateTime
     */
    private function now($by = null)
    {
        $now = $this->now ?: $this->now = new DateTime('now');
        $now = clone($now);
        if ($by) {
            $now->add(new \DateInterval($by));
        }
        return $now;
    }
        
        
    /**
     * @param ObjectManager $em
     */
    private function populateDb($em)
    {
        $now = new DateTime('now');
        
        // create 2 projects: work and life. 
        $work = new Project([
            'name' => 'work', 
            'done_by' => $this->now('P7D'),
        ]);
        $em->persist($work);

        $life = new Project([
            'name' => 'life',
            'done_by' => $this->now('P14D'),
        ]);
        $em->persist($life);
        
        // create basic groups for work and life.
        $group1 = new Group([
            'name' => 'tasks',
            'is_active' => Group\GroupIsActive::ACTIVE,
            'done_by' => $this->now('P3D'),
        ]);
        $group1->setProject($work);
        $em->persist($group1);

        $group2 = new Group([
            'name' => 'plans', 
            'is_active' => Group\GroupIsActive::ACTIVE,
            'done_by' => $this->now('P4D'),
        ]);
        $group2->setProject($life);
        $em->persist($group2);
        
        // add tasks for work/tasks
        $task1 = new Task([
            'title' => 'brain storm', 
            'done_by' => $this->now('P1D'),
        ]);
        $task1->done();
        $task1->setGroup($group1);
        $em->persist($task1);

        $task1 = new Task([
            'title' => 'write proposal', 
            'done_by' => $this->now('P1D'),
        ]);
        $task1->setGroup($group1);
        $em->persist($task1);

        $task2 = new Task([
            'title' => 'develop product', 
            'done_by' => $this->now('P2D'),
        ]);
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