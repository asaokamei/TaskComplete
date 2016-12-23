<?php

namespace AppBundle\Entity\Tasks;

use AppBundle\Entity\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="task_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tasks\GroupRepository")
 */
class Group
{
    use EntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * A Group have one Project.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tasks\Project", inversedBy="groups")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * @var Project
     */
    private $project;

    /**
     * Project Many Groups.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tasks\Task", mappedBy="group")
     * @var ArrayCollection|Task[]
     */
    private $tasks;

    /**
     * Group constructor.
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return Task[]|ArrayCollection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}

