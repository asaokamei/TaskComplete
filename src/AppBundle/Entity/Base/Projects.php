<?php
namespace AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Projects
 *
 * @package AppBundle\Base
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Base\ProjectsRepository")
 */
class Projects
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
    private $isActive;

    /**
     * @var string
     * @ORM\Column(name="project_name", type="string")
     */
    private $name;

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
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}