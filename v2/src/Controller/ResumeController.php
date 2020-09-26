<?php

namespace App\Controller;

use App\Entity\Resume;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AdminController
{
    /**
     * @Route("/admin/resume", name="adminResumeListing")
     */
    public function listing()
    {
        $resumes = $this->getDoctrine()
            ->getRepository(Resume::class)
            ->findAll();

        return $this->render('back/resume/listing.html.twig', [
            'resumes' => $resumes,
            'title' => 'Liste des versions du CV',
            'active' => (new ReflectionClass($this))->getShortName(),
        ]);
    }

    /**
     * @Route("/admin/resume/add", name="adminResumeAdd")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ReflectionException
     */
    public function add(Request $request)
    {
        $resume = new Resume();
        return $this->generateForm($request, $resume);
    }

    /**
     * @Route("/admin/resume/{id}", name="adminResumeEdit", requirements={"id"="\d+"})
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     * @throws ReflectionException
     */
    public function edit(Request $request, int $id)
    {
        $resume = $this->getDoctrine()
            ->getRepository(Resume::class)
            ->findOneBy([
                'id' => $id
            ]);
        return $this->generateForm($request, $resume);
    }

    /**
     * @Route("/admin/resume/delete/{id}", name="adminResumeDelete", requirements={"id"="\d+"})
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function delete(int $id)
    {
        // @todo: methods=delete
        $resume = $this->getDoctrine()
            ->getRepository(Resume::class)
            ->findOneBy([
                'id' => $id
            ]);

        if ($resume) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resume);
            $entityManager->flush();
            $status = 'deleted';
        } else {
            $status = 'notFound';
        }

        return $this->redirectToRoute('adminResumeListing', [
            'status' => $status,
        ]);
    }

    /**
     * @param Request $request
     * @param Resume $resume
     * @return RedirectResponse|Response
     * @throws ReflectionException
     */
    protected function generateForm(Request $request, Resume $resume)
    {
        $actionLabel = !$resume->getId() ? 'Ajouter' : 'Modifier';

        $form = $this->createFormBuilder($resume)
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => $actionLabel])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateTime = new \DateTime();
            $resume = $form->getData();
            $resume->setDateTime($dateTime->getTimestamp());
            $resume->setFilesize(strlen($resume->getText()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resume);
            $entityManager->flush();

            return $this->redirectToRoute('adminResumeListing');
        }

        return $this->render('back/resume/form.html.twig', [
            'form' => $form->createView(),
            'actionLabel' => $actionLabel,
            'title' => $actionLabel . ' un utilisateur',
            'active' => (new ReflectionClass($this))->getShortName(),
        ]);
    }
}
