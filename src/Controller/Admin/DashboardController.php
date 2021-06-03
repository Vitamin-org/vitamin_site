<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Template;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/start_page.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Vitamin Site');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Рецепты', 'fas fa-list', Recipe::class);
        yield MenuItem::linkToCrud('Ингредиенты', 'fas fa-list', Ingredient::class);
        yield MenuItem::linkToCrud('Шаблоны', 'fas fa-list', Template::class);
    }
}
