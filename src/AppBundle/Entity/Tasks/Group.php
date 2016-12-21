<?php

namespace AppBundle\Entity\Tasks;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Group
 *
 * @ORM\Table(name="task_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tasks\GroupRepository")
 */
class Group
{
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
     * @ManyToOne(targetEntity="AppBundle\Entity\Tasks\Project", inversedBy="groups")
     * @JoinColumn(name="project_id", referencedColumnName="id")
     * @var Project
     */
    private $project;

    /**
     * Project Many Groups.
     * @OneToMany(targetEntity="AppBundle\Entity\Tasks\Task", mappedBy="group")
     * @var ArrayCollection|Task[]
     */
    private $tasks;

    /**
     * Group constructor.
     */
    public function __construct()
    {
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
     * Set name
     *
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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

