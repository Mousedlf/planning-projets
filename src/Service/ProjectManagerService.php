<?php

namespace App\Service;

use App\Entity\Assignment;
use App\Entity\Person;
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

    public function assign(Project $project, DateTime $date, Person $person, float $allotedTime){

        $assignment = new Assignment();
        $assignment->setProject($project);
        $assignment->setDate($date);
        $assignment->setAllotedTime($allotedTime);
        $assignment->setPerson($person);

        $plannedHours = $project->getPlannedHours();
        $updatedPlannedHours = $plannedHours + $allotedTime;
        $project->setPlannedHours($updatedPlannedHours);

        $project->addPeopleWorkingOnIt($person);

        $this->manager->persist($project);
        $this->manager->persist($assignment);
        $this->manager->flush();
    }

    public function removeAssignment(Assignment $assignment){

        $project = $assignment->getProject();
        $person = $assignment->getPerson();

        $plannedHours = $project->getPlannedHours();
        $plannedHours -= $assignment->getAllotedTime();
        $project->setPlannedHours($plannedHours);

        $allAssignmentsLinkedToProject = $project->getAssignments();

        if(count($allAssignmentsLinkedToProject) == 1){
            $project->setPlannedHours(0);
        } 
        // encore des verifs au niveau du compteur necessaires
    
        $numberAssignementsOfPerson = 0;
        foreach($allAssignmentsLinkedToProject as $assignment){
            if($assignment->getPerson() == $person){
                $numberAssignementsOfPerson += 1;
            }
        }
        if($numberAssignementsOfPerson = 1 ){
            $project->removePeopleWorkingOnIt($person);
        }

        $this->manager->persist($project);
        $this->manager->remove($assignment);
        $this->manager->flush();
    }

}