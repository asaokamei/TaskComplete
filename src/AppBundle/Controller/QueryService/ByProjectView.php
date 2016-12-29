<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task\TaskStatus;
use DateTime;
use Doctrine\ORM\EntityManager;

class ByProjectView
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DateTime
     */
    private $now;

    /**
     * ProjectCrud constructor.
     *
     * @param EntityManager $em
     * @param DateTime      $now
     */
    public function __construct(EntityManager $em, DateTime $now = null)
    {
        $this->em = $em;
        $this->now = $now ?: new DateTime('now');
    }

    /**
     * @return Project[]
     */    
    public function getProjects()
    {
        $dq = $this->em->createQueryBuilder();
        return $dq->select('Project', 'Groups', 'Tasks')
            ->from(Project::class, 'Project')
            ->leftjoin('Project.groups', 'Groups')
            ->leftjoin('Groups.tasks', 'Tasks')
            ->where("
                Tasks.status = :active
                OR Tasks.status IS NULL 
                OR (Tasks.status = :done AND Tasks.doneAt >= :last24)
                ")
            ->setParameters([
                'active' => TaskStatus::ACTIVE,
                'done' => TaskStatus::DONE,
                'last24' => (clone $this->now)->sub(new \DateInterval('P1D'))->format('Y-m-d H:i:s'),
            ])
            ->getQuery()
            ->getResult();
    }
}