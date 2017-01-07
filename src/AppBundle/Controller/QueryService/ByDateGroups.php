<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Task;
use DateTime;
use Generator;
use IteratorAggregate;

class ByDateGroups implements IteratorAggregate
{
    const OVERDUE = 'overdue';
    const NO_DATE = 'no-date';
    const LATER = 'later';

    /**
     * @var DateTime[]
     */
    private $list = [
        'today'     => 'P1D',
        'tomorrow'  => 'P2D',
        'this week' => 'P7D'
    ];

    /**
     * @var array
     */
    private $tasks = [];

    /**
     * @var DateTime
     */
    private $now;

    /**
     * ByDateType constructor.
     *
     * @param DateTime $now
     */
    public function __construct(DateTime $now = null)
    {
        $this->now                  = $now ?: new DateTime('now');
        $this->tasks[self::NO_DATE] = [];
        $this->tasks[self::OVERDUE] = [];
        foreach ($this->list as $type => $interval) {
            $this->list[$type]  = (clone $this->now)->add(new \DateInterval($interval));
            $this->tasks[$type] = [];
        }
        $this->tasks[self::LATER] = [];
    }

    /**
     * @param Task $task
     */
    public function add(Task $task)
    {
        $type                 = $this->getType($task);
        $this->tasks[$type][] = $task;
    }

    /**
     * @param Task $task
     * @return string
     */
    private function getType(Task $task)
    {
        $doneBy = $task->getDoneBy();
        if (!$doneBy) {
            return self::NO_DATE;
        }
        if ($doneBy < $this->now) {
            return self::OVERDUE;
        }
        foreach ($this->list as $type => $date) {
            if ($doneBy <= $date) {
                return $type;
            }
        }

        return self::LATER;
    }

    /**
     * @return Generator
     */
    public function getIterator()
    {
        foreach ($this->tasks as $type => $tasks) {
            yield $type => new ByDateGroupTasks($tasks);
        }
    }
}