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
        return $this->render("main/homepage.html.twig", [
            "title" => "Главная страница"
            ]);
    }

    /**
     * @Route("/recipe", name="recipe_list")
     */
    public function getRecipeList(Request $request): Response
    {
        $include_vitamins = preg_split ( '/[^\p{Cyrillic}0-9]+/u' , $request->get('include_vitamins'),-1 , PREG_SPLIT_NO_EMPTY );
        $include_ingredients = preg_split ( '/[^\p{Cyrillic}0-9]+/u' , $request->get('include_ingredients'), -1 , PREG_SPLIT_NO_EMPTY );
        $exclude_vitamins = preg_split ( '/[^\p{Cyrillic}0-9]+/u' , $request->get('exclude_vitamins'), -1 , PREG_SPLIT_NO_EMPTY );
        $exclude_ingredients = preg_split ( '/[^\p{Cyrillic}0-9]+/u' , $request->get('exclude_ingredients'), -1 , PREG_SPLIT_NO_EMPTY);


        $recipies = $arrayOfRecipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAllByFilters(
                $include_vitamins,
                $include_ingredients,
                $exclude_vitamins,
                $exclude_ingredients
            );
        return new Response($recipies[0]->getTitle());
        //return $this->render('main/recipe_list.html.twig');
    }

    /**
     * @Route("/recipe/{id}", name="one_recipe")
     */
    public function getOneRecipe(int $id): Response
    {
        $recipe = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException(
                'No recipe found for id '.$id
            );
        }

        return $this->render('main/one_recipe.html.twig', [
            'title' => $recipe->getTitle(),
            'name' => $recipe->getTitle(),
            'description' => $recipe->getDescription(),
            'ingredients' => $recipe->getIngredients(),
            'vitamins' => $recipe->getVitamins(),
        ]);
    }
}
