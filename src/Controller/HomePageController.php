<?php

namespace App\Controller;

use App\Entity\UserDetails;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomePageController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        return $this->render('home_page/index.html.twig', [
            'page_header' => 'Welcome to App Builders Pro',
            'page_title' => 'App Builders Pro',
        ]);
    }

    /**
     * @Route("/about-me", name="about_me")
     */
    public function aboutMe()
    {
        $userId = 6;
        $userDetails = $this->getDoctrine()
            ->getRepository(UserDetails::class)
            ->findOneBy(['user'=>6]);

        $userBio = '';
        if ($userDetails) {
            $userBio = $userDetails->getBio();
        }

        return $this->render('home_page/about_me.html.twig', [
            'page_title' => 'About Me',
            'userBio'=>$userBio,
        ]);
    }
}
