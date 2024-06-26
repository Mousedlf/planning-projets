<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         return $this->render("home/home.html.twig"); 
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion des heures')
            ->renderSidebarMinimized()
    ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToCrud('Projets', 'fa fa-folder-open', Project::class),
            MenuItem::linkToCrud('Personnes', 'fa fa-person', Person::class),
            MenuItem::linkToRoute('#', 'fa fa-calendar-days', 'planning'),
          
        ];
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            // ->addJsFile('/assets/app.js') 
            ->addJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js')
        ;
    }


}
