<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
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
        
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","POST"})
     * @isGranted("PIN_CREATE")
    **/
     
    public function create(Request $request,UserRepository $repository, EntityManagerInterface $manager): Response
    {
  
        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($this->getUser());
            $manager->persist($pin);
            $manager->flush();
            $this->addFlash('success', "Pin successfully added");
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', [
            "form" => $form->createView(),
        ]);
    }


    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit",  methods={"PUT", "GET"})
     * @Security("is_granted('PIN_MANAGE', pin )")
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $manager): Response
    {

    $form= $this->createForm(PinType::class, $pin, [
        'method'=> 'put',
    ]);

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $manager->flush();
        $this->addFlash('success', "Pin successfully edited");
        return $this->redirectToRoute('app_home');
    }
        return $this->render('pins/edit.html.twig', [
            'form' => $form->createView(),
            'pin' => $pin,
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete",  methods={"DELETE"})
     * 
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $manager): Response
    {
        
        $this->denyAccessUnlessGranted('PIN_MANAGE',$pin);

        $csrf= $request->request->get('csrf_token'); 

        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $csrf) ) {
            $manager->remove($pin);
            $manager->flush();
            $this->addFlash('info', "Pin successfully deleted");
        }
        
        return $this->redirectToRoute('app_home');
    }
}
