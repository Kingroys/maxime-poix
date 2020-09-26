<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="adminDefault")
     */
    public function default()
    {
        return $this->render('back/admin.html.twig', [
            'title' => 'Administration',
        ]);
    }
}
