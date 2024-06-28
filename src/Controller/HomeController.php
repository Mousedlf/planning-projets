<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Entity\Person;
use App\Entity\Project;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Service\ProjectManagerService;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\String\u;

#[Route('/admin')]
class HomeController extends AbstractController
{
    private $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }


    #[Route('/planning', name: 'planning')]
    public function index(ProjectRepository $projectRepository,  PersonRepository $personRepository)
    {
        $projects = [];
        $people = $personRepository->findAll() ;
        $json = "";


        return $this->render('home/index.html.twig', [
            'projects' => $projects,
            'people'=> $people,
            'json'=> $json,
        ] );

    }

    #[Route('/planning/{id}', name: 'planning_person')]
    public function getAssignmentsOfPerson(Person $person, ProjectRepository $projectRepository, PersonRepository $personRepository, ProjectManagerService $projectManagerService)
    {
        $projects = $projectRepository->findAll();
        $people = $personRepository->findAll() ;

        $json = $projectManagerService->getAssignmentsOfPerson($person);

        return $this->render('home/index.html.twig', [
            'json'=> $json,
            'projects' => $projects,
            'people'=> $people,
        ] );
   

    }


    #[Route('/assign/{id}/{date}', name: 'add_assignment')]
    public function assignProject(Request $request,$date, ProjectRepository $projectRepository,ProjectManagerService $projectManagerService, Project $project, PersonRepository $personRepository): Response
    {
        $referer = $request->headers->get('referer');
        $bricolageId = substr($referer, strpos($referer, "id%5D=") + 6);
        
        $person = $personRepository->findBy(['id'=> $bricolageId]); // recup du titre du tableau

        $allotedTime = 8 ; // a recup d'un formulaire, max 8 càd 1 jour ?
        $d = new DateTime($date);


        $projectManagerService->assign($project, $d, $person[0], $allotedTime);
        
        $projects = $projectRepository->findAll();
        $people = $personRepository->findAll() ;

        $json = $projectManagerService->getAssignmentsOfPerson($person[0]);

      //  dd($json);

        return $this->render('home/index.html.twig', [
            'json'=> $json,
            'projects' => $projects,
            'people'=> $people,
        ] );
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
