<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() : Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", name="wild_show",  requirements={"slug"="[a-z\-0-9]+"})
     * @param string $slug
     * @return Response
     */
    public function show(string $slug = 'Aucune série sélectionnée, veuillez choisir une série') : Response
    {
        if (empty($slug)){
            return $this->render('wild/show.html.twig', [
                'slug' => $slug]);
        } else {
            $slug = str_replace("-", " ", $slug);
            $slug = ucwords($slug);

            return $this->render('wild/show.html.twig', [
                'slug' => $slug,
            ]);
        }
    }
}