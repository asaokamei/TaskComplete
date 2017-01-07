<?php
namespace AppBundle\Entity\Tasks\Task;

use AppBundle\Entity\EnumTrait;

class TaskStatus
{
    use EnumTrait;

    const ACTIVE = 'active';
    const DONE = 'done';

    protected $choices = [
        self::ACTIVE => 'active',
        self::DONE   => 'done',
    ];

    /**
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }
}