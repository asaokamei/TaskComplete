<?php
namespace AppBundle\AppService\ProjectCrud;

use AppBundle\AppService\Common\ServiceDTO;
use AppBundle\AppService\GroupCrud\GroupDTO;
use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
        $this->em      = $em;
        $this->builder = $builder;
    }

    /**
     * @param int $id
     * @return Project|null|object
     */
    public function findById($id)
    {
        $projectRepo = $this->em->getRepository(Project::class);
        $project     = $projectRepo->findOneBy(['id' => $id]);

        return $project;
    }

    /**
     * @return FormInterface
     */
    public function getCreateForm()
    {
        $project = new ProjectDTO();
        foreach (range(0, 2) as $i) {
            $project->groups[$i] = new GroupDTO();
        }
        $form = $this->builder->createBuilder(FormType::class, $project)
            ->add('name', TextType::class, ['label' => 'Project name'])
            ->add('doneBy', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('name', TextType::class, ['required' => true])
            ->add('groups', CollectionType::class, [
                'entry_type' => ProjectGroupType::class,
            ])
            ->getForm();

        return $form;
    }

    /**
     * @param Request $request
     * @return ServiceDTO
     */
    public function create(Request $request)
    {
        $form = $this->getCreateForm();
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return ServiceDTO::failed($form)->setMessage('please check inputs.');
        }
        /** @var ProjectDTO $projectDto */
        $projectDto = $form->getData();
        $em         = $this->em;

        $project = new Project($projectDto->toArray());
        $em->persist($project);
        foreach ($projectDto->groups as $groupDTO) {
            if (!$groupDTO->name || !$groupDTO->doneBy) {
                continue;
            }
            $group = new Group($groupDTO->toArray());
            $group->setProject($project);
            $em->persist($group);
        }
        $em->flush();

        return ServiceDTO::success()->setCreatedId($project->getId());
    }

    /**
     * @param Project $project
     * @return FormInterface
     */
    public function getUpdateForm(Project $project)
    {
        $data = new ProjectDTOUpdate($project->toArray());
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