<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s tble');
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @Route("/wild/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="wild_show")
     * @param string $slug The slugger
     * @return Response
     */
    public function show(?string $slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);

        /* code écrit pour la quête Symfony 05 le routing avancé
         * if (empty($slug)){
            return $this->render('wild/show.html.twig', [
                'slug' => $slug]);
        } else {
            $slug = str_replace("-", " ", $slug);
            $slug = ucwords($slug);

            return $this->render('wild/show.html.twig', [
                'slug' => $slug,
            ]);
        }
        */
    }

    /**
     * @Route("/wild/showbycategory/{category}", defaults={"category" = null}, name="wild_showbycategory")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        //Utilise la méthode  du repository Category::class apropriée afin de récupérer l'objet Category
        // correspondant à la chaine de caratére récupérée depuis l’URL.

        //Dans la même méthode, à partir de l’objet Category fraîchement récupéré, appelle la méthode findBy()
        // ou la méthode magique appropriée sur le repository Program::class afin de parcourir toutes les séries
        // liées à la catégorie courante

        //Enfin, ajoute une limite de 3 séries et un tri par id décroissant à la récupération des séries.

        return $this->render('wild/category.html.twig', [
            'categoryName' => $categoryName
        ]);

    }
}