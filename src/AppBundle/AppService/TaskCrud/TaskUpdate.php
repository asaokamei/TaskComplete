<?php
namespace AppBundle\AppService\TaskCrud;

use AppBundle\AppService\Common\ServiceDTO;
use AppBundle\Entity\Tasks\Task;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskUpdate extends TaskCrud
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
     * @param Task $data
     * @return FormInterface
     */
    public function getUpdateForm(Task $data)
    {
        $task = new TaskDTO();
        $task->fill($data->toArray());
        $form = $this->builder->createBuilder(FormType::class, $task)
            ->add('title', TextType::class, ['label' => 'Task name', 'required' => true])
            ->add('doneBy', DateType::class, ['widget' => 'single_text', 'required' => false, 'label' => 'done by'])
            ->add('details', TextareaType::class, ['required' => false, 'label' => 'details'])
            ->getForm();

        return $form;
    }

    /**
     * @param Task    $task
     * @param Request $request
     * @return ServiceDTO
     */
    public function update(Task $task, Request $request)
    {
        $form = $this->getUpdateForm($task);
        $form = $form->handleRequest($request);
        if (!$form->isValid()) {
            return ServiceDTO::failed($form)->setMessage('please check inputs. ');
        }
        $task->fill($form->getData()->toArray());
        $this->em->persist($task);
        $this->em->flush();

        return ServiceDTO::success();
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