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
use Symfony\Component\HttpFoundation\Request;

class TaskCreate extends TaskCrud
{
    /**
     * TaskCrud constructor.
     *
     * @param EntityManager $em
     * @param FormFactory   $builder
     */
    public function __construct(EntityManager $em, $builder)
    {
        parent::__construct($em, $builder);
    }

    /**
     * @return FormInterface
     */
    public function getCreateForm()
    {
        $task = new TaskDTO();
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
     * @return FormInterface
     */
    public function create(Project $project, Group $group, Request $request)
    {
        if ($group->getProject() !== $project) {
            throw new \InvalidArgumentException();
        }
        $form = $this->getCreateForm();
        $form = $form->handleRequest($request);
        if (!$form->isValid()) {
            return $form;
        }
        $task = new Task($form->getData()->toArray());
        $task->setGroup($group);

        $this->em->persist($task);
        $this->em->flush();
        
        return $form;
    }
}