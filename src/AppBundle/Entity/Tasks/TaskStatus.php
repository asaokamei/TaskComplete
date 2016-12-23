<?php
namespace AppBundle\Entity\Tasks;

use AppBundle\Entity\EnumTrait;

class TaskStatus
{
    use EnumTrait;
    
    const ACTIVE = 'active';
    const DONE = 'done';
    
    protected static $choices = [
        self::ACTIVE => 'active',
        self::DONE => 'done',
    ];
}