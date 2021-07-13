<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $repository): Response
    {

        $pins = $repository->findBy([], ["createdAt" => "DESC"]);

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="get")
     */
    public function show(Pin $pin): Response
    {
        dump($pin);
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $pin = new Pin;

        /**
         * Remplacer la création du fromulaire dans le controleur 
         * par un Object de type App\Form\PinType 
         * on fait l'appel à la méthode createForm et non pas createFormBuilder 
         */

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($pin);
            $manager->flush();
            $this->addFlash('success', "Pin added with success");
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit",  methods={"GET", "PUT"})
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $manager): Response
    {

        /**
         * Il faut impérativement passer method pour changer la méthode de l'action PinController::edit 
         * @var array les options utilisées pour construire le formulaire | à passer dans la createForm
         * 
         */
        //$options = ["method" => "PUT"];


        /**
         * @var FormInterface
         */
        $form = $this->createForm(PinType::class, $pin, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush($pin);
            return $this->redirectToRoute('app_home');
        }


        return $this->render('pins/edit.html.twig', [
            'form' => $form->createView(),
            'pin' => $pin
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete",  methods={"ALL"})
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $manager): Response
    {
        $csrf= $request->request->get('csrf_token'); 

        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $csrf) ) {
            $manager->remove($pin);
            $manager->flush();
        }
        
        return $this->redirectToRoute('app_home');
    }
}
