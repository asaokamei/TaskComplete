<?php
namespace AppBundle\Controller\Project;

use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;

class ProjectCrud
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
     * @param int $id
     * @return Project|null|object
     */
    public function findById($id)
    {
        $projectRepo = $this->em->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $id]);
        
        return $project;
    }
}