<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Actor;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/wild", name="wild_index")
     * @param Request $request
     * @return Response A response instance
     */
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table');
        }
        $form = $this->createForm(ProgramSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $programs = $programRepository ->searchProgram($data);
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
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
    /**
     * @Route("/wild/actor", name="wild_all_actor")
     * @return Response A response instance
     */
    public function showActor(): Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        if (!$actors) {
            throw $this->createNotFoundException('No actor found in actor\'s table');
        }

        return $this->render('wild/show_actor.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * @Route("/wild/actor/{id}", name="wild_actor")
     */
    public function actorProgram(Actor $actor): Response
    {
        $programs = $actor->getPrograms();

        return $this->render('wild/actor.html.twig', [
            'actor' => $actor,
            'programs' => $programs
        ]);
    }
}