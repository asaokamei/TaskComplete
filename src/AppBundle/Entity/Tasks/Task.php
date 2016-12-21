<?php

namespace AppBundle\Entity\Tasks;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Task
 *
 * @ORM\Table(name="task_task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tasks\TaskRepository")
 */
class Task
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
     * @ManyToOne(targetEntity="AppBundle\Entity\Tasks\Group", inversedBy="tasks")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     * @var Group
     */
    private $group;

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
     * Set status
     *
     * @param string $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Set details
     *
     * @param string $details
     *
     * @return Task
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
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
     * Set doneBy
     *
     * @param \DateTime $doneBy
     *
     * @return Task
     */
    public function setDoneBy($doneBy)
    {
        $this->doneBy = $doneBy;

        return $this;
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
    public function setDoneAt($doneAt)
    {
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

