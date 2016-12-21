<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;

class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function indexAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            $users = $repository->findAll();

            $count = count($users);

            for ($i = 0; $i < $count; $i++) {
                if ($form->getData()->getEmail() == $users[$i]->getEmail()) {
                    return $this->redirect($this->generateUrl('homepage'));
                }
            }

            $user->setEmail(mb_strtolower($user->getEmail()));
            $user->setToken('Token');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('AppBundle:Registration:index.html.twig', ['form' => $form->createView()]);
    }
}
