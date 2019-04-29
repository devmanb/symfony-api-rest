<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PersonalController extends AbstractController
{
    /**
     * @Route("/api/personal", name="personal", methods={"GET"})
     */
    public function index()
    {
       return $this->json(['page_pesonnal' => 'ok']);
    }
}
