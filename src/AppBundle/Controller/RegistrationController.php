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
        $registration = new User();

        $form = $this->createForm(RegistrationType::class, $registration);

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

            $registration->setToken('Token');
            //$user = new User();
            /*$user->setEmail($form->getData()->getEmail());
            $user->setPassword($form->getData()->getPassword());
            $user->setRole('ROLE_USER');
            $user->setName($form->getData()->getName());
            $date = new \DateTime('now');
            $user->setCreated($date);
            $user->setUpdated($date);
            $user->setEnabled(false);
            $user->setToken(1);*/

            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('AppBundle:registration:index.html.twig', ['form' => $form->createView()]);
    }
}
