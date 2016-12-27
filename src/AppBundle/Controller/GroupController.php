<?php
namespace AppBundle\Controller;

use AppBundle\Controller\CrudService\GroupCrud;
use AppBundle\Controller\CrudService\ProjectCrud;
use AppBundle\Entity\Tasks\Group;
use AppBundle\Entity\Tasks\Project;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * @Config\Route("/groups/create/{project_id}", name="group-create")
     * @Config\Method({"GET"})
     * @return Response
     */
    public function createAction()
    {
        return $this->render('');
    }

    /**
     * @Config\Route("/groups/create/{project_id}")
     * @Config\Method({"POST"})
     * @param Request $request
     * @param int     $project_id
     * @return Response
     */
    public function insertAction(Request $request, $project_id)
    {
        /** @var ProjectCrud $crud */
        $crud = $this->get('app.project-crud');
        if (!$project = $crud->findById($project_id)) {
            throw new \InvalidArgumentException('no such project id.');
        }
        /** @var GroupCrud $gCrud */
        $gCrud = $this->get('app.group-crud');
        $form = $gCrud->getCreateForm($project_id);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            $this->addFlash('notice', 'Please check input for group');
            return $this->redirectToRoute("project-detail", ['id' => $project_id]);
        }
        $this->createNewGroup($project, $form->getData());
        $this->addFlash('message', 'created a new group. ');
        return $this->redirectToRoute("project-detail", ['id' => $project_id]);
    }

    /**
     * @Config\Route("/groups/{id}")
     * @Config\Method({"POST"})
     * @param int $id
     * @return Response
     */
    public function updateAction($id)
    {
        return $this->render('task/task/show.html.twig', [
            'project_id' => $id,
        ]);
    }

    /**
     * @Config\Route("/groups/{id}")
     * @Config\Method({"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteAction($id)
    {
    }

    /**
     * @param Project $project
     * @param array $data
     */
    private function createNewGroup($project, array $data)
    {
        $group   = new Group($data);
        $group->setProject($project);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();
    }
}