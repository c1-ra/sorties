<?php

namespace App\Controller\API;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class APILieuController extends AbstractController
{
    /**
     * Liste des lieux
     * @Route("/lieux", name="liste_lieux_ville", methods={"GET"})
     */
    public function liste(Request $request, LieuRepository $lieuRepo) {
        $lieux = $lieuRepo->createQueryBuilder('qb')
            ->where('qb.ville = :ville')
            ->setParameter('ville', $request->query->get('ville'))
            ->getQuery()
            ->getResult();

        $responseArray = array();
        foreach($lieux as $lieu) {
            $responseArray[] = array(
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
                'rue' => $lieu->getRue(),
                'codePostal' => $lieu->getVille()->getCodePostal(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude()
            );
        }
        return new JsonResponse($responseArray);
    }
}
