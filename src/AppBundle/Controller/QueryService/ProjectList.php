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
        $projects = $repo->findBy([]);
        
        return $projects;
    }
}