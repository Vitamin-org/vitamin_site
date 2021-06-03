<?php


namespace App\Controller;


use App\Entity\Recipe;
use App\Entity\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    /**
     * @Route("/template/{id}", name="template_link")
     */
    public function getOneTemplate(int $id): Response
    {
        $template = $this->getDoctrine()
            ->getRepository(Template::class)
            ->find($id);

        $include_vitamins = $template->getIncludeVitamins();
        $include_ingredients = $template->getIncludeIngredients();
        $exclude_vitamins = $template->getExcludeVitamins();
        $exclude_ingredients = $template->getExcludeIngredients();


        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAllByFilters(
                $include_ingredients,
                $include_vitamins,
                $exclude_ingredients,
                $exclude_vitamins
            );

        return $this->render('main/recipe_list.html.twig', [
            'title' => 'Найденные рецепты',
            'recipes' => $recipes,
            'template' => $template
        ]);
    }

    /**
     * @Route("/template/", name="template")
     */
    public function getTemplates(Request $request): Response
    {
        $name = preg_split( '/([",\[\]\:{}]+)/u', $request->get("template"),-1 , PREG_SPLIT_NO_EMPTY )[1];


        $template = $this->getDoctrine()
            ->getRepository(Template::class)
            ->findOneByName($name);

        $include_vitamins = $template->getIncludeVitamins();
        $include_ingredients = $template->getIncludeIngredients();
        $exclude_vitamins = $template->getExcludeVitamins();
        $exclude_ingredients = $template->getExcludeIngredients();


        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAllByFilters(
                $include_ingredients,
                $include_vitamins,
                $exclude_ingredients,
                $exclude_vitamins
            );

        return $this->render('main/recipe_list.html.twig', [
            'title' => 'Найденные рецепты',
            'recipes' => $recipes,
            'template' => $template
        ]);
    }
}