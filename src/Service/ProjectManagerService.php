<?php

namespace App\Service;

use App\Entity\Assignment;
use App\Entity\Person;
use App\Entity\Project;
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

    public function getAssignmentsOfPerson(Person $person){

        $allAssignments = $person->getAssignments();
        $json = "";
        $data = [];

        foreach($allAssignments as $as){

            $event = [
                'id'=> $as->getId(),
                'start'=> $as->getDate()->format('Y-m-d'),
                'title'=> $as->getProject()->getName(),
            ];
            $data[]=$event;
        }

        $json = json_encode($data);

        return $json;
    }

    public function getAssignmentsOfSelectedDay($date, Person $person){

        $assignmentsOfSelectedDay = [];
        $assignments = $person->getAssignments();

        foreach($assignments as $as){
            if($as->getDate() == $date){
                $assignmentsOfSelectedDay[] = $as;
            }
        }

        dd($assignmentsOfSelectedDay);


    }


    public function assign(Project $project, DateTime $date, Person $person, float $allotedTime){

        $plannedHoursOnProject = 0;
        $allAssignmentsLinkedToProject = $project->getAssignments();
        

        // si deja une tache a cette date enlever tache et remplacer.
        


        $assignment = new Assignment();
        $assignment->setProject($project);
        $assignment->setDate($date);
        $assignment->setAllotedTime($allotedTime);
        $assignment->setPerson($person);


        foreach($allAssignmentsLinkedToProject as $as){
            $plannedHoursOnProject += $as->getAllotedTime();
        }
        $updatedPlannedHours = $plannedHoursOnProject + $allotedTime;

        $project->setPlannedHours($updatedPlannedHours);
        $project->addPeopleWorkingOnIt($person);

        $this->manager->persist($project);
        $this->manager->persist($assignment);


      //  dd($assignment );

      //  $this->manager->flush();
    }

    public function removeAssignment(Assignment $assignment){

        $project = $assignment->getProject();
        $person = $assignment->getPerson();
        $allAssignmentsLinkedToProject = $project->getAssignments();
        $numberOfAssignementsOfPerson = 0;
        $totalPlannedHours = 0;


        // Planned Hours
        foreach($allAssignmentsLinkedToProject as $as){
            $totalPlannedHours += $as->getAllotedTime();
        }
        $afterRemovedAssignementPlannedHours = $totalPlannedHours - $assignment->getAllotedTime();
        $project->setPlannedHours($afterRemovedAssignementPlannedHours);

        if(count($allAssignmentsLinkedToProject) == 1){
            $project->setPlannedHours(0);
        } 
       
        // People working on project
        foreach($allAssignmentsLinkedToProject as $as){
            if($assignment->getPerson() == $person){
                $numberOfAssignementsOfPerson += 1;
            }
        }
        if($numberOfAssignementsOfPerson == 1 ){
            $project->removePeopleWorkingOnIt($person); // revoir peopleWorkingOnit
        }


        $this->manager->remove($assignment);



        $this->manager->persist($project);
        $this->manager->flush();
    }

}