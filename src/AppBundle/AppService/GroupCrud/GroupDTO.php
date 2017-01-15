<?php
namespace AppBundle\AppService\GroupCrud;

use AppBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

class GroupDTO
{
    use EntityTrait;

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
     * TaskDTO constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }
}