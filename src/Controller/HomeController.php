<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\ProjectManagerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();
        $assignments = [] ;

        if($this->getUser() != null){
            $currentUser =$this->getUser()->getProfile();
            $assignments = $currentUser->getAssignments();
        }

        
        return $this->render('home/index.html.twig', [
            'projects' => $projects,
            'assignments'=> $assignments,
        ] );
    }

    #[Route('/assign/{id}', name: 'app_assign')]
    public function assignProject(ProjectManagerService $projectManagerService, Project $project): Response
    {
        $user = $this->getUser();
        $date = new DateTime(); // recup date du calendrier
        $allotedTime = 2 ; // a recup d'un formulaire, max 8 càd 1 jour

        $projectManagerService->assign($project, $date, $user, $allotedTime);

        return $this->redirectToRoute('app_home', [
            
        ] );
    }

    #[Route('/remove/assignment/{id}', name: 'remove_assignment')]
    public function removeAssignment(ProjectManagerService $projectManagerService, Assignment $assignment): Response
    {
        $projectManagerService->removeAssignment($assignment);

        return $this->redirectToRoute('app_home', [
            
        ] );
    }
}
