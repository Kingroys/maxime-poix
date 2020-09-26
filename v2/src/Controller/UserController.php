<?php

namespace App\Controller;

use App\Entity\User;
use ReflectionException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AdminController
{
    /**
     * @Route("/admin/user", name="adminUserListing")
     */
    public function listing()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('back/user/listing.html.twig', [
            'users' => $users,
            'title' => 'Liste des utilisateurs',
            'active' => (new \ReflectionClass($this))->getShortName(),
        ]);
    }

    /**
     * @Route("/admin/user/add", name="adminUserAdd")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ReflectionException
     */
    public function add(Request $request)
    {
        $user = new User();
        return $this->generateForm($request, $user);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse|Response
     * @throws ReflectionException
     */
    protected function generateForm(Request $request, User $user)
    {
        $actionLabel = !$user->getId() ? 'Ajouter' : 'Modifier';

        $form = $this->createFormBuilder($user)
            ->add('email', TextType::class)
            ->add('password', TextType::class)
            ->add('save', SubmitType::class, ['label' => $actionLabel])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('adminUserListing');
        }

        return $this->render('back/user/form.html.twig', [
            'form' => $form->createView(),
            'actionLabel' => $actionLabel,
            'title' => $actionLabel . ' un utilisateur',
            'active' => (new \ReflectionClass($this))->getShortName(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="adminUserEdit", requirements={"id"="\d+"})
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     * @throws ReflectionException
     */
    public function edit(Request $request, int $id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy([
                'id' => $id
            ]);
        return $this->generateForm($request, $user);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="adminUserDelete", requirements={"id"="\d+"})
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function delete(int $id)
    {
        // @todo: methods=delete
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy([
                'id' => $id
            ]);

        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $status = 'deleted';
        } else {
            $status = 'notFound';
        }

        return $this->redirectToRoute('adminUserListing', [
            'status' => $status,
        ]);
    }
}
