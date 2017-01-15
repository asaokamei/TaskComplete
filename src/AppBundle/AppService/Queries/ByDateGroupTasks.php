<?php
namespace AppBundle\AppService\Queries;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
use IteratorAggregate;

class ByDateGroupTasks implements IteratorAggregate
{
    /**
     * @var Task[][]
     */
    private $tasks = [];

    /**
     * @var Group[]
     */
    private $groups;

    /**
     * TaskByProject constructor.
     *
     * @param Task[] $tasks
     */
    public function __construct(array $tasks)
    {
        foreach ($tasks as $task) {
            $group = $task->getGroup();
            $id    = $group->getId();
            if (!isset($this->groups[$id])) {
                $this->groups[$id] = $group;
            }
            $this->tasks[$id][] = $task;
        }
    }

    /**
     * @param int $group_id
     * @return Group
     */
    public function getGroup($group_id)
    {
        if (!isset($this->groups[$group_id])) {
            throw new \InvalidArgumentException();
        }

        return $this->groups[$group_id];
    }

    /**
     * @param int $group_id
     * @return Project
     */
    public function getProject($group_id)
    {
        $group = $this->getGroup($group_id);

        return $group->getProject();
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->tasks as $group_id => $tasks) {
            usort($tasks, function (Task $a, Task $b) {
                if ($a->getStatus() === $b->getStatus()) {
                    return $a->getDate() <=> $b->getDate();
                }

                return $a->getStatus() <=> $b->getStatus();
            });
            yield $group_id => $tasks;
        }
    }
}