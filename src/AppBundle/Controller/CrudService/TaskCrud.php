<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Router;

class TaskCrud
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactory
     */
    private $builder;

    /**
     * @var Router
     */
    private $router;

    /**
     * TaskCrud constructor.
     *
     * @param EntityManager $em
     * @param FormFactory   $builder
     */
    public function __construct(EntityManager $em, $builder)
    {
        $this->em = $em;
        $this->builder = $builder;
    }

    /**
     * @param int $id
     * @return Task|null|object
     */
    public function findById($id)
    {
        $taskRepo = $this->em->getRepository(Task::class);
        $task  = $taskRepo->findOneBy(['id' => $id]);

        return $task;
    }

    /**
     * flushes/saves entity to database. 
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * @return FormInterface
     */
    public function getCreateForm()
    {
        $form = $this->builder->createBuilder(FormType::class)
            ->add('title', TextType::class, ['label' => 'Task name', 'required' => true])
            ->add('done_by', DateType::class, ['widget' => 'single_text', 'required' => false, 'label' => 'done by'])
            ->add('details', TextareaType::class, ['required' => false, 'label' => 'details'])
            ->getForm();

        return $form;
    }

    /**
     * @param array $data
     * @return FormInterface
     */
    public function getUpdateForm(array $data)
    {
        $form = $this->builder->createBuilder(FormType::class, $data)
            ->add('title', TextType::class, ['label' => 'Task name', 'required' => true])
            ->add('done_by', DateType::class, ['widget' => 'single_text', 'required' => false, 'label' => 'done by'])
            ->add('details', TextareaType::class, ['required' => false, 'label' => 'details'])
            ->getForm();

        return $form;
    }

    /**
     * @param Project $project
     * @param Group   $group
     * @param array   $data
     */
    public function create(Project $project, Group $group, array $data)
    {
        if ($group->getProject() !== $project) {
            throw new \InvalidArgumentException();
        }
        $task = new Task($data);
        $task->setGroup($group);
        
        $this->em->persist($task);
        $this->em->flush();
    }

    /**
     * @param Task  $task
     * @param array $data
     */
    public function update(Task $task, array $data)
    {
        $task->fill($data);
        $this->em->persist($task);
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

    /**
     * @param Task $task
     */
    public function delete($task)
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}