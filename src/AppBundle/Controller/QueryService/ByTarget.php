<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Task\TaskStatus;
use DateTime;
use Doctrine\ORM\EntityManager;

class ByTarget
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
     * @return Group[]
     */
    public function getGroups()
    {
        $dq = $this->em->createQueryBuilder();

        return $dq->select('g', 't', 'p')
            ->from(Group::class, 'g')
            ->join('g.tasks', 't')
            ->join('g.project', 'p')
            ->where("
                t.status = :active
                OR (t.status = :done AND t.doneAt >= :last24)
                ")
            ->orderBy('g.doneBy')
            ->addOrderBy('t.status')
            ->addOrderBy('t.doneBy')
            ->addOrderBy('t.id')
            ->setParameters([
                'active' => TaskStatus::ACTIVE,
                'done'   => TaskStatus::DONE,
                'last24' => (clone $this->now)->sub(new \DateInterval('P1D'))->format('Y-m-d H:i:s'),
            ])
            ->getQuery()
            ->getResult();
    }
}