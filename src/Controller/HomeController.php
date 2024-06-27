<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Entity\Project;
use App\Repository\AssignmentRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Service\ProjectManagerService;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class HomeController extends AbstractController
{
    private $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }


    #[Route('/planning', name: 'planning')]
    public function index(ProjectRepository $projectRepository, PersonRepository $personRepository, AssignmentRepository $assignmentRepository)
    {
        $projects = $projectRepository->findAll();
        $people = $personRepository->findAll() ;

        // overtime
        // exact nb of hours :  if($project->getDuration() > $project->getPlannedHours()){

        $allAssignments = $assignmentRepository->findAll();

      //  dd(count($allAssignments));
        $json = "";
        $data = [];

        foreach($allAssignments as $as){

            $event = [
                'id'=> $as->getId(),
                'start'=> $as->getDate()->format('Y-m-d'),
              //  'project'=> $as->getProject()->getName(),
            ];
            $data[]=$event;
        }

        // dd($data);
        $json = json_encode($data);
        // dd($json);

         return $this->render('home/index.html.twig', [
             'projects' => $projects,
             'people'=> $people,
             'json'=> $json
         ] );
    }


    #[Route('/assign/{id}', name: 'add_assignment')]
    public function assignProject(ProjectManagerService $projectManagerService, Project $project, PersonRepository $personRepository): Response
    {
        $person = $personRepository->findBy(['id'=> 1]); // recup du titre du tableau
        $date = new DateTime(); // recup date du calendrier
        $allotedTime = 2 ; // a recup d'un formulaire, max 8 càd 1 jour ?

        $projectManagerService->assign($project, $date, $person[0], $allotedTime);
        
        $url = $this->adminUrlGenerator
            ->setRoute('planning')
            ->generateUrl();

        return $this->redirect($url);
    }


    #[Route('/remove/assignment/{id}', name: 'remove_assignment')]
    public function removeAssignment(ProjectManagerService $projectManagerService, Assignment $assignment): Response
    {
        $projectManagerService->removeAssignment($assignment);
        
        $url = $this->adminUrlGenerator
            ->setRoute('planning')
            ->generateUrl();

        return $this->redirect($url);
    }

}
