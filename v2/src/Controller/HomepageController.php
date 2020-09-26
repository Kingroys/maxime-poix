<?php

namespace App\Controller;

use App\Entity\Resume;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET","HEAD"}, name="homepage")
     */
    public function default() {
        return $this->render('front/homepage.html.twig', [
            'title'  => 'Maxime POIX - Ingénieur études et développement',
            'resume' => $this->getResume(),
        ]);
    }

    /**
     * @Route("/cv", methods={"GET","HEAD"}, name="resumeDisplay")
     */
    public function resume() {
        return $this->render('front/resume.html.twig', [
            'resume' => $this->getResume(),
        ]);
    }

    /**
     * @return Resume|object|null
     */
    protected function getResume() {
        $resume = $this->getDoctrine()
            ->getRepository(Resume::class)
            ->findOneBy([], ['id' => 'DESC']);
        return $resume ? $resume->getText() : 'WIP...';
    }
}
