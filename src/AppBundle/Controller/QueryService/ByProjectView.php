<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;

class ByProjectView
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
     * @return Project[]
     */    
    public function getProjects()
    {
        $projects = $this->em->getRepository(Project::class);
        return $projects->findAll();
    }
}