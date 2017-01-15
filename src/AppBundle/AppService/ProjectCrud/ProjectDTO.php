<?php
namespace AppBundle\AppService\ProjectCrud;

use AppBundle\AppService\GroupCrud\GroupDTO;
use AppBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback()
     */
    public function validateAtLeastOneGroup(ExecutionContextInterface $context)
    {
        $groups = $this->groups;
        $this->groups = [];
        foreach($groups as $group) {
            if ($group->name) {
                $this->groups[] = $group;
            }
        }
        if (empty($this->groups)) {
            $context->buildViolation('please add at least one target.')
                    ->addViolation()
            ;
        }
    }
}