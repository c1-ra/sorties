<?php

namespace App\Controller;

use App\ClassPHP\SortiesFiltreForm;
use App\Entity\Sortie;
use App\Form\SortiesFiltreType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/accueil/", name="accueil")
     */
    public function afficherListeSorties(Request $request, SortieRepository $sortieRepo, CampusRepository $campusRepo): Response
    {
        $user = $this->getUser();
        $formData = new SortiesFiltreForm();

        //gestion des filtres en sessions s'il y en a
       if ($request->getSession()->has('dernierForm')) {
           // je remets tous les derniers filtres en place s'il y en avait déjà eus
            $formData = $request->getSession()->get('dernierForm');
            // un peu différent pour campus qui est un champ entité, je le récupère depuis la base :
           // condition sur l'id campus null (pour sélectionner tous les campus) ou non
           if ($request->getSession()->get('dernierForm')->getCampus()) {
               $campus = $campusRepo->find($request->getSession()->get('dernierForm')->getCampus()->getId());
           } else {
               $campus = null;
           }
        } else {
            $campus = $user->getCampus();
        }

        $formData->setCampus($campus);

        $sortiesFiltreForm = $this->createForm(SortiesFiltreType::class, $formData);
        $sortiesFiltreForm->handleRequest($request);

        if ($sortiesFiltreForm->isSubmitted()) {
            // j'enregistre les filtres en session
            $request->getSession()->set('dernierForm', $sortiesFiltreForm->getData());
        }

        $sorties = $sortieRepo->rechercheListeSorties($user, $formData);

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'SortieController',
            'formData' => $formData,
            'listeSorties' => $sorties,
            'sortiesFiltreForm' => $sortiesFiltreForm->createView()
        ]);
    }

    /**
     * @Route("/creation-sortie", name="crea-sortie")
     */
    public function creerSortie(Request $request, EtatRepository $etatRepo, EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();
        $user = $this->getUser();
        $sortie->setCampus($user->getCampus());

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $sortie->setOrganisateur($user);
            $sortie->setCampus($user->getCampus());
            //cas où l'utilisateur a cliqué sur publier pour enregistrer et publier en même temps : l'état ne sera pas le même
            if ($sortieForm->get('publier')->isClicked()) {
                $sortie->setEtat($etatRepo->findOneBy(['libelle' => 'Ouverte']));
                $this->addFlash('success', 'Sortie créée et publiée !');
            } else {
                $sortie->setEtat($etatRepo->findOneBy(['libelle' => 'Créée']));
                $this->addFlash('success', 'Sortie créée !');
            }
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('accueil');
        }

        return $this->render('crea-sortie/index.html.twig', [
            'controller_name' => 'SortieController',
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * @Route("/inscription/{id}", name="inscription", requirements={"id"="\d+"})
     */
    public function inscriptionSortie(SortieRepository $sortieRepo, EntityManagerInterface $em, $id): Response
    {
        $sortie = $sortieRepo->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'existe pas.');
        }

        $sortie->addParticipant($this->getUser());

        $em->persist($sortie);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/desistement/{id}", name="desistement", requirements={"id"="\d+"})
     */
    public function desistementSortie(SortieRepository $sortieRepo, EntityManagerInterface $em, $id): Response
    {
        $sortie = $sortieRepo->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'existe pas.');
        }

        $sortie->removeParticipant($this->getUser());

        $em->persist($sortie);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/annulation/{id}", name="annulation", requirements={"id"="\d+"})
     */
    public function annulationSortie(Request $request, SortieRepository $sortieRepo, EtatRepository $etatRepo, EntityManagerInterface $em, $id): Response
    {
        $sortie = $sortieRepo->find($id);

        if (!$sortie || $sortie->getEtat()->getLibelle() != "Ouverte") {
            throw $this->createNotFoundException('La sortie n\'existe pas ou n\'est pas publiée.');
        }

        if ($this->getUser() == $sortie->getOrganisateur()) {
            $motifAnnulationForm = $this->createFormBuilder()
                ->add('motif', TextareaType::class, [
                    'label' => 'Motif :'
                ])
                ->getForm();

            $motifAnnulationForm->handleRequest($request);

            if ($motifAnnulationForm->isSubmitted() && $motifAnnulationForm->isValid()) {
                $sortie->setMotifAnnulation(($motifAnnulationForm->getData())['motif']);
                $sortie->setEtat($etatRepo->findOneBy(['libelle' => 'Annulée']));
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'Sortie annulée !');

                return $this->redirectToRoute('accueil');
            }
            return $this->render('annulation-sortie/annulation-sortie.html.twig', [
                'controller_name' => 'SortieController',
                'sortie' => $sortie,
                'motifAnnulationForm' => $motifAnnulationForm->createView()
            ]);

        } else {
            $this->addFlash('danger', 'Seul l\'organisateur peut annuler une sortie !');
            return $this->redirectToRoute('accueil');
        }
    }

    /**
     * @Route("/sortie/{id}", name="affichage-sortie", requirements={"id"="\d+"})
     */
    public function affichageSortie(SortieRepository $sortieRepo, $id): Response
    {
        $sortie = $sortieRepo->find($id);
        // pas de filtre sur les sorties archivées, elles seront visibles si l'utilisateur tape l'id dans l'URL
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'existe pas.');
        }

        return $this->render('affichage-sortie/index.html.twig', [
            'controller_name' => 'SortieController',
            'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/publication/{id}", name="publication", requirements={"id"="\d+"})
     */
    public function publierSortie(SortieRepository $sortieRepo, EtatRepository $etatRepo, EntityManagerInterface $em, $id): Response
    {
        $sortie = $sortieRepo->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'existe pas.');
        }
        if ($this->getUser() == $sortie->getOrganisateur()) {
            $sortie->setEtat($etatRepo->findOneBy(['libelle' => 'Ouverte']));
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Sortie publiée !');
        } else {
            $this->addFlash('danger', 'Seul l\'organisateur peut publier une sortie !');
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/modification/{id}", name="modif-sortie", requirements={"id"="\d+"})
     */
    public function modifierSortie(Request $request, SortieRepository $sortieRepo, EntityManagerInterface $em, $id): Response
    {
        $sortie = $sortieRepo->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'existe pas.');
        }

        if ($sortie->getOrganisateur() == $this->getUser()) {
            //nécessaire pour ré-injecter l'objet en base dans l'objet lors de la soumission du form
            $campusEnBase = $sortie->getCampus();
            $sortieModifForm = $this->createForm(SortieType::class, $sortie);
            $sortieModifForm->handleRequest($request);

            if ($sortieModifForm->isSubmitted() && $sortieModifForm->isValid()) {
                $sortie->setCampus($campusEnBase);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('success', 'Sortie modifiée !');
                return $this->redirectToRoute('accueil');
            }

            return $this->render('modif-sortie/index.html.twig', [
                'controller_name' => 'SortieController',
                'sortieForm' => $sortieModifForm->createView(),
                'sortie' => $sortie
            ]);

        } else {
            $this->addFlash('danger', 'Seul l\'organisateur peut modifier une sortie !');
            return $this->redirectToRoute('accueil');
        }
    }

    /**
     * @Route("/supprimer/{id}", name="suppression-sortie", requirements={"id"="\d+"})
     */
    public function supprimerSortie(SortieRepository $sortieRepo, EntityManagerInterface $em, $id): Response
    {
        $sortie = $sortieRepo->find($id);
        if (!$sortie || $sortie->getEtat()->getLibelle() != "Créée") {
            throw $this->createNotFoundException('La sortie n\'existe pas ou a déjà été publiée.');
        }
        if ($sortie->getOrganisateur() == $this->getUser()) {
            $em->remove($sortie);
            $em->flush();
            $this->addFlash('success', 'Sortie supprimée !');
        } else {
            $this->addFlash('danger', 'Seul l\'organisateur peut supprimer une sortie !');
        }
        return $this->redirectToRoute('accueil');
    }

}
