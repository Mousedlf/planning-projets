<?php

namespace App\Service;

use App\Entity\Assignment;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\AssignmentRepository;
use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ProjectManagerService
{
    public function __construct(
        public EntityManagerInterface $manager,
        public AssignmentRepository $assignmentRepository,
    ){}

    public function getAll(ProjectRepository $projectRepository){
        $projects = $projectRepository->findAll();
        return $projects;
    }

    public function assign(Project $project, DateTime $date, User $user, float $allotedTime){

        $assignment = new Assignment();
        $assignment->setProject($project);
        $assignment->setDate($date);
        $assignment->setProfile($user->getProfile());
        $assignment->setAllotedTime($allotedTime);

        $plannedHours = $project->getPlannedHours();

        $updatedPlannedHours = $plannedHours + $allotedTime;

        $project->setPlannedHours($updatedPlannedHours);


        $this->manager->persist($project);
        $this->manager->persist($assignment);
        $this->manager->flush();

    }

    public function removeAssignment(Assignment $assignment){

        $project = $assignment->getProject();

        $plannedHours = $project->getPlannedHours();
        $plannedHours -= $assignment->getAllotedTime();
        $project->setPlannedHours($plannedHours);

        $this->manager->remove($assignment);
        $this->manager->flush();
    }

    // $totalHours = 0;

    // $assignements = $project->getAssignments();
    
    // foreach($assignements as $assignment){
    //     $totalHours += $assignment->getAllotedTime();
    // };

}