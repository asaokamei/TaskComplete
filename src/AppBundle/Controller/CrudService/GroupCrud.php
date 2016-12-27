<?php
namespace AppBundle\Controller\CrudService;

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


}