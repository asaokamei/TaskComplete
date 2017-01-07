<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectDTO
{
    use EntityTrait;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var \DateTime
     * @Assert\Type("\DateTime")
     */
    public $doneBy;

    /**
     * @var GroupDTO[]
     */
    public $groups;

    /**
     * TaskDTO constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }
}