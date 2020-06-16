<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/my_profile")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="my_profile")
     */
    public function account()
    {
        $user = $this->getUser();
        return $this->render('user/user.html.twig', [ 'user' => $user]);
    }

}