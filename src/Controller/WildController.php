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
            'h1' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/wild/show{page}", name="wild_show")
     * @param string $page
     * @return Response
     */
    public function show(string $page) : Response
    {
        $page = str_replace();

        return $this->render('wild/show.html.twig', [
            'page' => $page,
        ]);
    }

}