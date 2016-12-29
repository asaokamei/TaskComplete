<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
use IteratorAggregate;

class TaskByProject implements IteratorAggregate
{
    /**
     * @var Task[]
     */
    private $tasks = [];

    /**
     * @var Project[]
     */
    private $projects;

    /**
     * TaskByProject constructor.
     *
     * @param Task[] $tasks
     */
    public function __construct(array $tasks)
    {
        foreach($tasks as $task) {
            $project = $task->getGroup()->getProject();
            $this->projects[$project->getId()] = $project;
            $this->tasks[$project->getId()][] = $task;
        }
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach($this->tasks as $id => $tasks) {
            yield $this->projects[$id]->getName() => $tasks;
        }
    }
}