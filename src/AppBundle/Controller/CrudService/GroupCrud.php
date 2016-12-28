<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Router;

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
     * @var Router
     */
    private $router;

    /**
     * ProjectCrud constructor.
     *
     * @param EntityManager $em
     * @param FormFactory   $builder
     * @param Router        $router
     */
    public function __construct(EntityManager $em, $builder, $router)
    {
        $this->em = $em;
        $this->builder = $builder;
        $this->router = $router;
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
     * @param int $project_id
     * @return Form|FormInterface
     */
    public function getCreateForm($project_id)
    {
        $form = $this->builder
            ->createNamedBuilder('NewGroup', FormType::class)
            ->setMethod('POST')
            ->setAction($this->router->generate('group-create', ['project_id' => $project_id]));

        /** @var FormBuilder $form */
        return $form
            ->add('name', TextType::class, ['required' => true, 'label' => 'Group name'])
            ->getForm();
    }
    
    /**
     * @param Project $project
     * @param array $data
     */
    public function create($project, array $data)
    {
        $group   = new Group($data);
        $group->setProject($project);
        $em = $this->em;
        $em->persist($group);
        $em->flush();
    }

}