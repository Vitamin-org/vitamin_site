<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        return $this->render("main/homepage.html.twig");
    }

    /**
     * @Route("/recipe", name="recipe_list")
     */
    public function getRecipeList(Request $request): Response
    {
        $request->query->get('include_vitamins', []);
        $request->query->get('include_ingredients', []);
        $request->query->get('exclude_vitamins', []);
        $request->query->get('exclude_ingredients', []);
        return $this->render( 'main/recipe_list.html.twig', [
                                   'recipes'=> [ ['id' => 56, ] ] ] );
    }

    /**
     * @Route("/recipe/{id}", name="one_recipe")
     */
    public function getOneRecipe(): Response
    {
        return $this->render('main/one_recipe.html.twig');
    }
}
