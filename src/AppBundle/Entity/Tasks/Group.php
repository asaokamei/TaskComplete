<?php

namespace AppBundle\Entity\Tasks;

use AppBundle\Entity\EntityTrait;
use AppBundle\Entity\Tasks\Generic\DoneDate;
use AppBundle\Entity\Tasks\Group\GroupIsActive;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="task_group")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Tasks\Group\GroupRepository")
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
     * @ORM\Column(name="is_active", type="string", length=16)
     */
    private $isActive = GroupIsActive::ACTIVE;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="done_by", type="datetime")
     */
    private $done_by;

    /**
     * A Group have one Project.
     * @var Project
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tasks\Project", inversedBy="groups")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * Project Many Groups.
     * @var ArrayCollection|Task[]
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tasks\Task", mappedBy="group")
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
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive === GroupIsActive::ACTIVE;
    }

    /**
     * @return Group
     */
    public function close()
    {
        $this->isActive = GroupIsActive::INACTIVE;

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

    /**
     * Get doneBy
     *
     * @return DoneDate
     */
    public function getDoneBy()
    {
        return new DoneDate($this->done_by);
    }
}

