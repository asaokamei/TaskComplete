<?php
namespace AppBundle\AppService\Queries;

use AppBundle\Entity\Tasks\Task;
use AppBundle\Entity\Tasks\Task\TaskStatus;
use DateTime;
use Doctrine\ORM\EntityManager;

class ByDateView
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
        $this->em  = $em;
        $this->now = $now ?: new DateTime('now');
    }

    /**
     * @return ByDateGroups
     */
    public function getTasks()
    {
        $tasks = $this->loadTasks();
        $types = new ByDateGroups();
        foreach ($tasks as $task) {
            $types->add($task);
        }

        return $types;
    }

    /**
     * @return Task[]
     */
    private function loadTasks()
    {
        $dq = $this->em->createQueryBuilder();

        return $dq->select('Task', 'g', 'p')
            ->from(Task::class, 'Task')
            ->join('Task.group', 'g')
            ->join('g.project', 'p')
            ->where("
                Task.status = :active
                OR Task.status IS NULL 
                OR (Task.status = :done AND Task.doneAt >= :last24)
                ")
            /*            ->orderBy('Project.id')
                        ->orderBy('Group.id') */
            ->setParameters([
                'active' => TaskStatus::ACTIVE,
                'done'   => TaskStatus::DONE,
                'last24' => (clone $this->now)->sub(new \DateInterval('P1D'))->format('Y-m-d H:i:s'),
            ])
            ->getQuery()
            ->getResult();
    }
}