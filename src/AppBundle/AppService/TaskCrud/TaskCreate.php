<?php
namespace AppBundle\AppService\TaskCrud;

use AppBundle\AppService\Common\ServiceDTO;
use AppBundle\AppService\Common\ServiceInterface;
use AppBundle\AppService\Common\Transaction;
use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use AppBundle\Entity\Tasks\Task;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskCreate extends TaskCrud implements ServiceInterface
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * TaskCrud constructor.
     *
     * @param EntityManager $em
     * @param FormFactory   $builder
     */
    public function __construct(EntityManager $em, $builder)
    {
        parent::__construct($em, $builder);
        $this->transaction = new Transaction($em, $this);
    }

    /**
     * @return FormInterface
     */
    public function getCreateForm()
    {
        $task = new TaskDTO(new \DateTime());
        $form = $this->builder->createBuilder(FormType::class, $task)
            ->add('title', TextType::class, ['label' => 'Task name', 'required' => true])
            ->add('doneBy', DateType::class, ['widget' => 'single_text', 'required' => false, 'label' => 'done by'])
            ->add('details', TextareaType::class, ['required' => false, 'label' => 'details'])
            ->getForm();

        return $form;
    }

    /**
     * @param Project $project
     * @param Group   $group
     * @param Request $request
     * @return ServiceDTO
     */
    public function create(Project $project, Group $group, Request $request)
    {
        $request->attributes->add([
            'project' => $project,
            'group' => $group,
        ]);
        return $this->transaction->run($request);
    }

    /**
     * @param Request $request
     * @return ServiceDTO
     */
    public function run($request)
    {
        $project = $request->attributes->get('project');
        $group = $request->attributes->get('group');
        if ($group->getProject() !== $project) {
            throw new \InvalidArgumentException();
        }
        $form = $this->getCreateForm();
        $form = $form->handleRequest($request);
        if (!$form->isValid()) {
            return ServiceDTO::failed($form)->setMessage('please check inputs.');
        }
        $task = new Task($form->getData()->toArray());
        $task->setGroup($group);

        $this->em->persist($task);

        return ServiceDTO::success();
    }
}