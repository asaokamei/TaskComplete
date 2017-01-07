<?php

namespace AppBundle\Entity\Tasks\Task;

use AppBundle\Entity\Tasks\Generic\DoneDate;
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
     * @return DoneDate
     */
    public function format()
    {
        $date = $this->status->is(TaskStatus::DONE)
            ? $this->doneAt
            : $this->doneBy;

        return new DoneDate($date);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->format();
    }
}