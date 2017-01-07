<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    use EntityTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @var string
     *
     */
    public $details;

    /**
     * @var \DateTime
     * @Assert\Type("\DateTime")
     */
    public $doneBy;

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