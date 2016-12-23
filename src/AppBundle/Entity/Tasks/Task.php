<?php

namespace AppBundle\Entity\Tasks;

use AppBundle\Entity\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task_task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tasks\TaskRepository")
 */
class Task
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
     * @ORM\Column(name="status", type="string", length=16)
     */
    private $status = TaskStatus::ACTIVE;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="done_by", type="datetime", nullable=true)
     */
    private $doneBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="done_at", type="datetime", nullable=true)
     */
    private $doneAt;

    /**
     * A Group have one Project.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tasks\Group", inversedBy="tasks")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * @var Group
     */
    private $group;

    /**
     * Task constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
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
        return $this->status === TaskStatus::ACTIVE;
    }

    /**
     * Get status
     *
     * @param null|\DateTime $doneAt
     * @return string
     */
    public function done($doneAt = null)
    {
        $this->setDoneAt($doneAt);
        $this->status = TaskStatus::DONE;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Get doneBy
     *
     * @return \DateTime
     */
    public function getDoneBy()
    {
        return $this->doneBy;
    }

    /**
     * Set doneBy
     *
     * @param \DateTime $doneAt
     *
     * @return Task
     */
    protected function setDoneAt($doneAt)
    {
        if (!$doneAt) {
            $doneAt = new \DateTime('now');
        } elseif (is_string($doneAt)) {
            $doneAt = new \DateTime($doneAt);
        } elseif (!$doneAt instanceof \DateTime) {
            throw new \InvalidArgumentException();
        }

        $this->doneAt = $doneAt;

        return $this;
    }

    /**
     * Get doneAt
     *
     * @return \DateTime
     */
    public function getDoneAt()
    {
        return $this->doneAt;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $groups
     */
    public function setGroup($groups)
    {
        $this->group = $groups;
    }
}

