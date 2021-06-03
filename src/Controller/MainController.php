<?php

namespace App\Controller;

use App\Entity\Ingredient;
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
        $ingredients = $this->getDoctrine()
                    ->getRepository(Ingredient::class)
                    ->findAll();

         $vitamins = $this->getDoctrine()
                    ->getRepository(Ingredient::class)
                    ->findAllVitamins();


        return $this->render("main/homepage.html.twig", [
            "title" => "Главная страница",
            "ingredients" => $ingredients,
            "vitamins" => $vitamins,
            ]);
    }

    /**
     * @Route("/recipe", name="recipe_list")
     */
    public function getRecipeList(Request $request): Response
    {
        $include_vitamins = $this->getParameters($request->get('include_vitamins'));
        $include_ingredients = $this->getParameters($request->get('include_ingredients'));
        $exclude_vitamins = $this->getParameters($request->get('exclude_vitamins'));
        $exclude_ingredients = $this->getParameters($request->get('exclude_ingredients'));

        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAllByFilters(
                $include_ingredients,
                $include_vitamins,
                $exclude_ingredients,
                $exclude_vitamins
            );

//        $recipe = $this->getDoctrine()
//            ->getRepository(Recipe::class)
//            ->find(2);



        return $this->render('main/recipe_list.html.twig', [
            'title' => 'Найденные рецепты',
            'recipes' => $recipes
        ]);
    }

    private function getParameters(? string $line): array
    {
        $ar = array();
        if ($line == null) return $ar;

        $result = preg_split( '/([",\[\]\:{}]+)/u', $line,-1 , PREG_SPLIT_NO_EMPTY );
        foreach ($result as $el)
        {
            if ($el != "value") array_push($ar, $el);
        }
        return $ar;
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
            'recipe' => $recipe,
        ]);
    }
}
