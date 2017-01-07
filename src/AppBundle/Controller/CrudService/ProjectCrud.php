<?php
namespace AppBundle\Controller\CrudService;

use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProjectCrud
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
     * @return Project|null|object
     */
    public function findById($id)
    {
        $projectRepo = $this->em->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $id]);
        
        return $project;
    }

    /**
     * @return FormInterface
     */
    public function getCreateForm()
    {
        $form = $this->builder->createBuilder(FormType::class)
            ->add('name', TextType::class, ['required' => true, 'label' => 'Project name'])
            ->add('done_by', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('group_name', TextType::class, ['required' => true])
            ->getForm();

        return $form;
    }

    /**
     * @param array $data
     * @return int
     */
    public function create(array $data)
    {
        $group_data = ['name' => $data['group_name']];
        unset($data['group_name']);

        $project = new Project($data);
        $group   = new Group($group_data);
        $group->setProject($project);

        $em = $this->em;
        $em->persist($project);
        $em->persist($group);
        $em->flush();

        return $project->getId();
    }

    /**
     * @param Project $project
     * @return FormInterface
     */
    public function getUpdateForm(Project $project)
    {
        $data = new ProjectDTO($project->toArray());
        $form = $this->builder->createBuilder(FormType::class, $data)
            ->add('name', TextType::class, ['required' => true,])
            ->add('doneBy', DateType::class, ['required' => false, 'widget' => 'single_text'])
            ->getForm();

        return $form;
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return FormInterface
     */
    public function update(Project $project, Request $request)
    {
        $form = $this->getUpdateForm($project);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $form;
        }
        $data = $form->getData()->toArray();
        $project->fill($data);
        $this->em->flush();
        return $form;
    }

    /**
     * @param Project $project
     */
    public function delete(Project $project)
    {
        $project->close();
        $this->em->flush();
    }

}