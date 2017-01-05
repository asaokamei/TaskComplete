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
        $form = $this->builder->createBuilder(FormType::class)
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
}