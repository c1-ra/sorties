<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/profild", name="mon_profild")
     */
    public function index(): Response
    {
        return $this->render('mon_profil/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/profil", name="mon_profil")
     */
    public function editer(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response {

        $user = $this->getUser();

        $originalPassword = $user->getPassword();

        $profilForm = $this->createForm(ProfilFormType::class, $user);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid()) {

            $plainPassword = $profilForm->get('plainPassword')->getData();
            if (!empty($plainPassword)) {
                $password = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            } else {
                $user->setPassword($originalPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié!');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('mon_profil/index.html.twig', [
            'profilForm' => $profilForm->createView()
        ]);
    }

    /**
     * @Route("/profil/{id}", name="affichage-profil", requirements={"id"="\d+"})
     */
    public function affichageProfil(ParticipantRepository $participantRepo, $id): Response
    {
        $participant = $participantRepo->find($id);

        return $this->render('affichage-profil/index.html.twig', [
            'controller_name' => 'SortieController',
            'participant' => $participant
        ]);
    }

}
