<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
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
    }

    /**
     * @Route("/wild/category/{categoryName<^[a-z0-9-]+$>}", defaults={"categoryName" = null}, name="wild_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category Name has been sent to find a program in program\'s table.');
        }

        $categoryName = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        if (!$categoryName) {
            throw $this->createNotFoundException(
                'No program with ' . $categoryName . ' title, found in program\'s table.'
            );
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $categoryName], ['id' => 'desc'], 3);


        return $this->render('wild/category.html.twig', [
            'category' => $categoryName,
            'programs' => $programs,

        ]);
    }

    /**
     * @Route("/wild/program/{programTitle<^[a-z0-9-]+$>}", defaults={"programTitle" = null}, name="wild_program")
     * @param string $programTitle
     * @return Response
     */
    public function showByProgram(string $programTitle): Response
    {
        if (!$programTitle) {
            throw $this
                ->createNotFoundException('No program Name has been sent to find a program in program\'s table.');
        }
        $programTitle = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($programTitle)), "-")
        );

        $programTitle = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($programTitle)]);
        if (!$programTitle) {
            throw $this->createNotFoundException(
                'No program with ' . $programTitle . ' title, found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $programTitle], ['id' => 'asc']);

        return $this->render('wild/program.html.twig', [
            'program' => $programTitle,
            'seasons' => $seasons,

        ]);
    }

    /**
     * @Route("/wild/season/{id<^[0-9]+$>}", name="wild_season")
     * @param string $id
     * @return Response
     */
    public function showBySeason(string $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No season has been sent to find a program in season\'s table.');
        }

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);


        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $id], ['id' => 'asc']);
        if (!$episodes) {
            throw $this->createNotFoundException(
                'No episode found in episode\'s table.'
            );
        }

        return $this->render('wild/season.html.twig', [
            'program' => $season->getProgram(),
            'season' => $season,
            'episodes' => $episodes,

        ]);
    }
}