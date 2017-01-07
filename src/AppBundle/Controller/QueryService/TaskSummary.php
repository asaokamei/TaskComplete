<?php
namespace AppBundle\Controller\QueryService;

use AppBundle\Entity\Tasks\Generic\DoneDate;
use AppBundle\Entity\Tasks\Group\GroupIsActive;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task\TaskStatus;
use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityManager;

class TaskSummary
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DateTime
     */
    private $now;

    /**
     * ProjectCrud constructor.
     *
     * @param EntityManager $em
     * @param null|DateTime $now
     */
    public function __construct(EntityManager $em, DateTime $now = null)
    {
        $this->em  = $em;
        $this->now = $now ?: new DateTime('now');
    }

    /**
     * @return array
     */
    public function getSummary()
    {
        $countTargets = $this->countTargets();
        $countTasks = $this->countTasks();
        $countDone  = $this->countDoneTasks();
        $count24Hr  = $this->countDoneLastHours((clone $this->now)->sub(new \DateInterval('P1D')));
        $count48Hr  = $this->countDoneLastHours((clone $this->now)->sub(new \DateInterval('P2D')));
        $countWeek  = $this->countDoneLastHours((clone $this->now)->sub(new \DateInterval('P7D')));
        $projects   = $this->countProjects();
        foreach ($projects as $row => $project) {
            $id                            = $project['id'];
            $projects[$row]['done_by']     = $project['done_by'] ? new DoneDate(new DateTime($project['done_by'])) : '';
            $projects[$row]['count_target'] = $countTargets[$id] ?? 0;
            $projects[$row]['count_tasks'] = $countTasks[$id] ?? 0;
            $projects[$row]['count_done']  = $countDone[$id] ?? 0;
            $projects[$row]['count_24hr']  = $count24Hr[$id] ?? 0;
            $projects[$row]['count_48hr']  = $count48Hr[$id] ?? 0;
            $projects[$row]['count_week']  = $countWeek[$id] ?? 0;
            $projects[$row]['remaining']   = $projects[$row]['count_tasks'] - $projects[$row]['count_done'];
            $projects[$row]['completion']  = $projects[$row]['count_tasks']
                ? $projects[$row]['count_done'] / $projects[$row]['count_tasks'] * 100
                : null;
        }

        return $projects;
    }

    /**
     * @return array
     */
    private function countTargets()
    {
        $query  = $this->em->getConnection()->executeQuery("
            SELECT p.id AS id, count(g.id) AS count
            FROM task_group g
              JOIN task_project p ON(project_id=p.id)
            WHERE
              p.is_active = :project_active AND 
               g.is_active = :group_active
            GROUP BY p.id
        ", [
            'project_active' => Project\ProjectIsActive::ACTIVE,
            'group_active' => GroupIsActive::ACTIVE,
        ]);

        return $this->createSummaryById($query, 'count');
    }

    /**
     * @return array
     */
    private function countTasks()
    {
        $active = Project\ProjectIsActive::ACTIVE;
        $query  = $this->em->getConnection()->executeQuery("
            SELECT p.id AS id, count(t.id) AS count
            FROM task_task t
              JOIN task_group g ON(group_id=g.id)
              JOIN task_project p ON(project_id=p.id)
            WHERE
              p.is_active = '{$active}'
            GROUP BY p.id
        ");

        return $this->createSummaryById($query, 'count');
    }

    /**
     * @return array
     */
    private function countDoneTasks()
    {
        $active = Project\ProjectIsActive::ACTIVE;
        $done   = TaskStatus::DONE;
        $query  = $this->em->getConnection()->executeQuery("
            SELECT p.id AS id, count(t.id) AS count
            FROM task_task t
              JOIN task_group g ON(group_id=g.id)
              JOIN task_project p ON(project_id=p.id)
            WHERE
              p.is_active = '{$active}' AND 
              t.status = '{$done}'
            GROUP BY p.id
        ");

        return $this->createSummaryById($query, 'count');
    }

    /**
     * @param DateTime $doneAt
     * @return array
     */
    private function countDoneLastHours(DateTime $doneAt)
    {
        $active = Project\ProjectIsActive::ACTIVE;
        $done   = TaskStatus::DONE;
        $doneAt = $doneAt->format('Y-m-d H:i:s');
        $query  = $this->em->getConnection()->executeQuery("
            SELECT p.id AS id, count(t.id) AS count
            FROM task_task t
              JOIN task_group g ON(group_id=g.id)
              JOIN task_project p ON(project_id=p.id)
            WHERE
              p.is_active = '{$active}' AND 
              t.status = '{$done}' AND 
              t.done_at >= '{$doneAt}'
            GROUP BY p.id
        ");

        return $this->createSummaryById($query, 'count');
    }

    /**
     * @return array
     */
    private function countProjects()
    {
        $active   = Project\ProjectIsActive::ACTIVE;
        $query    = $this->em->getConnection()->executeQuery("
            SELECT * 
            FROM task_project p
            WHERE
              p.is_active = '{$active}'
            ORDER BY p.done_by ASC
        ");
        $projects = $this->createSummaryById($query);

        usort($projects, function ($p1, $p2) {
            $d1 = $p1['done_by'] ?? '2999-12-31 00:00:00';
            $d2 = $p2['done_by'] ?? '2999-12-31 00:00:00';
            if ($d1 == $d2) {
                return 0;
            }

            return $d1 > $d2 ? 1 : -1;
        });

        return $projects;
    }

    /**
     * @return Project[]
     */
    public function getProjects()
    {
        $projects = $this->em->getRepository(Project::class);

        return $projects->findAll();
    }

    /**
     * @param Statement   $query
     * @param string|null $column
     * @return array
     */
    private function createSummaryById($query, $column = null)
    {
        $projects = [];
        while ($row = $query->fetch()) {
            $projects[$row['id']] = $column ? $row[$column] : $row;
        }

        return $projects;
    }

}