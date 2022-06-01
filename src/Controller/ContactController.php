<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    #[Route('/', name: 'contact.index',  methods: ['GET'])]
    public function index() : Response
    {
        return $this->render('contact/index.html.twig');
    }


    #[Route('/contact/list', name: 'contact.list',  methods: ['GET'])]
    public function list(ContactRepository $contactRepository) : Response
    {
        $contacts = $contactRepository->findAll();
        // dd($contacts);
        
        return $this->render('contact/list.html.twig', compact('contacts'));
        // return $this->render('contact/list.html.twig', ['contacts' => $contacts]);
        // return $this->render('contact/list.html.twig', array('contacts' => $contacts));
    }


    #[Route('/contact/create', name: 'contact.create', methods: ['GET', 'POST'])]
    public function create(Request $request, ContactRepository $contactRepository) : Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) 
        {
            $contactRepository->add($contact, true);
            $this->addFlash("success", "Votre contact a été ajouté avec succès.");
            return $this->redirectToRoute('contact.list');
        }
        
        return $this->render('contact/create.html.twig', [
            "contact_create_form" => $form->createView()
        ]);
    }


    #[Route('/contact/edit/{id<\d+>}', name: 'contact.edit', methods: ['GET', 'POST'])]
    public function edit(Contact $contact, Request $request, ContactRepository $contactRepository) : Response
    {
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $contactRepository->add($contact, true);
            $this->addFlash('success', 'Le contact a été modifié avec succés.');
            return $this->redirectToRoute('contact.list');
        }

        return $this->render("contact/edit.html.twig", [
            "contact_edit_form" => $form->createView()
        ]);
    }


    #[Route('/contact/delete/{id<\d+>}', name: 'contact.delete', methods: ['POST'])]
    public function delete(Contact $contact, Request $request, ContactRepository $contactRepository) : Response
    {
        if ( $this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token')) ) 
        {
            $contactRepository->remove($contact, true);
            $this->addFlash('success', $contact->getFullName() . " a été retiré de la liste.");
        }

        return $this->redirectToRoute('contact.list');
    }




    
    



}
