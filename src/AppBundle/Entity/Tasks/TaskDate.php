<?php

namespace AppBundle\Entity\Tasks;

use DateTime;

class TaskDate
{
    /**
     * @var TaskStatus
     */
    private $status;

    /**
     * @var DateTime
     */
    private $doneBy;

    /**
     * @var DateTime
     */
    private $doneAt;

    /**
     * TaskDate constructor.
     *
     * @param TaskStatus $status
     * @param DateTime   $doneBy
     * @param DateTime   $doneAt
     */
    public function __construct(TaskStatus $status, $doneBy, $doneAt)
    {
        $this->status = $status;
        $this->doneBy = $doneBy;
        $this->doneAt = $doneAt;
    }

    /**
     * @param string $format
     * @return string
     */
    public function format($format = 'm/d')
    {
        $date = $this->status->is(TaskStatus::DONE) 
            ? $this->doneAt
            : $this->doneBy;
        if ($date) {
            return $date->format($format);
        }
        return '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }
}