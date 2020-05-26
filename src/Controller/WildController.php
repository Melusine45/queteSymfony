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
            throw $this->createNotFoundException('No program found in program\'s table');
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
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
     * @Route("/wild/show/{id}", name="wild_show")
     * @return Response
     */
    public function show(program $program): Response
    {
        if (!$program) {
            throw $this
                ->createNotFoundException('No program Name has been sent to find a program in program\'s table.');
        }

        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program], ['id' => 'asc']);

        return $this->render('wild/program.html.twig', [
            'program' => $program,
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


    /**
     * @Route("/wild/episode/{id}", name="wild_episode")
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

}
