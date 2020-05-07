<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavbarController extends AbstractController
{
    /**
     * @Route("/navbar", name="navbar")
     */
    public function index()
    {
        return $this->render('navbar/index.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }

    /**
     * @Route("/navbar_vertical", name="navbar_vertical")
     */
    public function navbar_vertical()
    {
        return $this->render('navbar/navbar_vertical.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }
    /**
     * @Route("/navbar_vertical_r", name="navbar_vertical_r")
     */
    public function navbar_vertical_right()
    {
        return $this->render('navbar/navbar_vertical_right.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }

    /**
     * @Route("/header", name="header_bottom")
     */
    public function header_bottom()
    {
        return $this->render('navbar/header.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }
}
