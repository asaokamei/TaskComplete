<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;

class ProjectList
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ProjectCrud constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return Project[]|array
     */
    public function getList()
    {
        $repo = $this->em->getRepository(Project::class);
        $projects = $repo->createQueryBuilder('p')
        ->select('p')
        ->where('p.isActive = :active')
        ->getQuery()
        ->setParameters([
            'active' => Project\ProjectIsActive::ACTIVE
        ])->getResult();

        usort($projects, function (Project $p1, Project $p2) {
            $d1 = $p1->getDoneBy()->format('Y-m-d H:i:s') ?: '2999-12-31 00:00:00';
            $d2 = $p2->getDoneBy()->format('Y-m-d H:i:s') ?: '2999-12-31 00:00:00';
            if ($d1 == $d2) return 0;
            return $d1 > $d2 ? 1 : -1;
        });

        return $projects;
    }
}