<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @var \DateTime
     */
    private $now;

    /**
     * TaskDTO constructor.
     *
     * @param \DateTime $now
     * @internal param array $data
     */
    public function __construct(\DateTime $now = null)
    {
        $this->now = $now ? clone($now): null;
        if ($this->now) {
            $this->now->setTime(0,0,0);
        }
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback()
     */
    public function validateDoneByHasFutureDate(ExecutionContextInterface $context)
    {
        if ($this->doneBy && $this->now && $this->doneBy < $this->now) {
            $context->buildViolation('please select future date as done-by.')
                    ->atPath('doneBy')
                    ->addViolation();
        }
    }
}