<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        $ingredients = $this->getDoctrine()
                    ->getRepository(Ingredient::class)
                    ->findAll();

        $vitamins = $this->getDoctrine()
                    ->getRepository(Ingredient::class)
                    ->findAllVitamins();

        $templates = $this->getDoctrine()
                    ->getRepository(Template::class)
                    ->findAll();


        return $this->render("main/homepage.html.twig", [
            "title" => "Главная страница",
            "ingredients" => $ingredients,
            "vitamins" => $vitamins,
            "templates" => $templates,
            ]);
    }
}
