<?php
namespace AppBundle\AppService\TaskCrud;

use AppBundle\Entity\Tasks\Task;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;

class TaskCrud
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactory
     */
    protected $builder;

    /**
     * TaskCrud constructor.
     *
     * @param EntityManager $em
     * @param FormFactory   $builder
     */
    public function __construct(EntityManager $em, $builder)
    {
        $this->em      = $em;
        $this->builder = $builder;
    }

    /**
     * @param int $id
     * @return Task|null|object
     */
    public function findById($id)
    {
        $taskRepo = $this->em->getRepository(Task::class);
        $task     = $taskRepo->findOneBy(['id' => $id]);

        return $task;
    }

    /**
     * @param int $id
     */
    public function activate(int $id)
    {
        $task = $this->findById($id);
        $task->activate();
        $this->em->flush();
    }

    /**
     * @param int $id
     */
    public function done(int $id)
    {
        $task = $this->findById($id);
        $task->done();
        $this->em->flush();
    }

    /**
     * @return string
     */
    public function getDoneActivateJS()
    {
        return <<<END_JS
    <script type="text/javascript">
        $(document).ready(function () {
            $("a.task-done").click(function () {
                var id = $(this).data('id');
                $.ajax({
                    type:'POST',
                    url:'/tasks/'+id+'/done'
                }).success(function () {
                    location.reload();
                }).error(function () {
                    alert('failed to done task')
                });
            });
            $("a.task-activate").click(function () {
                var id = $(this).data('id');
                $.ajax({
                    type:'POST',
                    url:'/tasks/'+id+'/activate'
                }).success(function () {
                    location.reload();
                }).error(function () {
                    alert('failed to activate task')
                });
            });
        });
    </script>

END_JS;

    }
}