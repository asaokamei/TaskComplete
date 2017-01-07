<?php
namespace AppBundle\Entity\Tasks;

use AppBundle\Entity\EntityTrait;
use AppBundle\Entity\Tasks\Generic\DoneDate;
use AppBundle\Entity\Tasks\Project\ProjectIsActive;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Projects
 *
 * @package AppBundle\Base
 * @ORM\Table("task_project")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Tasks\Project\ProjectsRepository")
 */
class Project
{
    use EntityTrait;

    /**
     * @var integer
     *
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
     *
     * @Assert\NotBlank()
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
     *
     * @var ArrayCollection|Group[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tasks\Group", mappedBy="project")
     */
    private $groups;

    /**
     * Project constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
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
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive === ProjectIsActive::ACTIVE;
    }

    /**
     * @return Project
     */
    public function close()
    {
        $this->isActive = ProjectIsActive::INACTIVE;

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
     * Get doneBy
     *
     * @return DoneDate
     */
    public function getDoneBy()
    {
        return new DoneDate($this->doneBy);
    }

    /**
     * @return ArrayCollection|Group[]
     */
    public function getGroups()
    {
        return $this->groups;
    }
}