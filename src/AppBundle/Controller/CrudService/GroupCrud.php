<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class GroupCrud
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
     * ProjectCrud constructor.
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
     * @return Group
     */
    public function findById($id)
    {
        $repo   = $this->em->getRepository(Group::class);
        $entity = $repo->findOneBy(['id' => $id]);

        return $entity;
    }

    /**
     * @return FormInterface
     */
    public function getCreateForm()
    {
        $group = new GroupDTO();
        $form = $this->builder
            ->createBuilder(FormType::class, $group)
            ->add('name', TextType::class, ['required' => true, 'label' => 'Group name'])
            ->add('doneBy', DateType::class, ['required' => true, 'label' => 'Done by', 'widget' => 'single_text'])
            ->getForm();
        return $form;
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return FormInterface
     */
    public function create(Project $project, Request $request)
    {
        $form = $this->getCreateForm();
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $form;
        }
        $data  = $form->getData()->toArray();
        $group = new Group($data);
        $group->setProject($project);
        $this->em->persist($group);
        $this->em->flush();
        
        return $form;
    }
    
    /**
     * @param Project $project
     * @param array $data
     */
    public function createX($project, array $data)
    {
        $group   = new Group($data);
        $group->setProject($project);
        $em = $this->em;
        $em->persist($group);
        $em->flush();
    }

}