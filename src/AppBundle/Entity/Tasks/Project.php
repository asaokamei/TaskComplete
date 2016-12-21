<?php
namespace AppBundle\Entity\Tasks;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Class Projects
 *
 * @package AppBundle\Base
 * @ORM\Table("task_project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tasks\ProjectsRepository")
 */
class Project
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="is_active", type="string", length=16)
     */
    private $isActive = ProjectIsActive::ACTIVE;

    /**
     * @var string
     * @ORM\Column(name="project_name", type="string")
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="done_by", type="datetime", nullable=true)
     */
    private $doneBy;

    /**
     * Project Many Groups.
     * @OneToMany(targetEntity="AppBundle\Entity\Tasks\Group", mappedBy="project")
     * @var ArrayCollection|Group[]
     */
    private $groups;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $isActive
     * @return Project
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set doneBy
     *
     * @param \DateTime $doneBy
     * @return Project
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
     * @return ArrayCollection|Group[]
     */
    public function getGroups()
    {
        return $this->groups;
    }
}