<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EmployeRepository;
use App\Repository\EntrepriseRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'app_entreprise')]
    public function index(EntrepriseRepository $er): Response
    {

        $entreprises = $er->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    #[Route('/entreprise/new', name: 'new_entreprise')]
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response{


        if(!$entreprise){
            $entreprise = new Entreprise();
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();

            if ($profilePictureFile) {

                $profilePictureName = $fileUploader->upload($profilePictureFile);
                $entreprise->setProfilePicture($profilePictureName);

                $entreprise = $form->getData();

                $em->persist($entreprise);
                $em->flush();
    
                return $this->redirectToRoute('app_entreprise');
            }
        }

        return $this->render('entreprise/new.html.twig', [
            'formCreate' => $form,
            'edit' => $entreprise->getId()
        ]);
    }

    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')]
    public function delete(Entreprise $entreprise, EntityManagerInterface $em){

        $em->remove($entreprise);
        $em->flush();
        
        return $this->redirectToRoute('app_entreprise');

    }

    #[Route('/entreprise/{id}/{idemploye}/hired', name: 'hired_employe')]
    public function hired(Entreprise $entreprise, Employe $employe, EntityManagerInterface $em)
    {
        $entreprise->addSalary($employe);
        $em->persist($entreprise);
        $em->flush();
        return $this->redirectToRoute('app_employe');

    }

    #[Route('/entreprise/{id}', name: 'show_entreprise')]
    public function show(Entreprise $entreprise, EmployeRepository $employeRepository): Response{

        $employes = $employeRepository->findUsersWithNullEntreprise();

        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'employes' => $employes
        ]);
    }
}
