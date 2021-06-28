<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function rechercheListeSorties($user, $formData) {
        $queryBuilder = $this->createQueryBuilder('s')
            ->groupBy('s.id')
            ->join('s.etat', 'e')
            ->addOrderBy('s.dateHeureDebut', 'DESC')
            ->addOrderBy('e.id', 'ASC')
            ->leftJoin('s.participants', 'p')
            ->join('s.organisateur', 'o')
            ->select('s', 'p', 'e', 'o')
            //Pour ne pas afficher les sorties archivées (plus d'un mois)
            ->andWhere('s.dateHeureDebut >= :dernierMois')
            ->setParameter('dernierMois', new \DateTime('-1 month'));

        if ($formData->isNonInscrit()) {
            $subQueryBuilder = $this->createQueryBuilder('s2')
                ->leftJoin('s2.participants', 'p2')
                ->andWhere('p2 = :user');
            $queryBuilder
                //->andWhere(:user member of s.participants)
                ->andWhere($queryBuilder->expr()->notIn('s.id', $subQueryBuilder->getDQL()))
                ->orWhere(':user = s.organisateur')
                ->setParameter('user', $user);
        }

        if ($formData->getRechercheNom()) {
            $queryBuilder
                ->andWhere('s.nom LIKE :rechercheNom')
                ->setParameter('rechercheNom', '%'.$formData->getRechercheNom().'%');
        }

        if ($formData->getDateMin()) {
            $queryBuilder
                ->andWhere('s.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $formData->getDateMin());
        }

        if ($formData->getDateMax()) {
            $queryBuilder
                ->andWhere('s.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $formData->getDateMax());
        }

        if ($formData->isOrga()) {
            $queryBuilder
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $user);
        }

        if ($formData->isInscrit()) {
            $queryBuilder
                ->andWhere(':user = p')
                ->setParameter('user', $user);
        }

        if ($formData->isPassees()) {
            $queryBuilder
                ->andWhere('s.dateHeureDebut <= CURRENT_DATE()');
        }

        if($formData->getCampus()) {
            $queryBuilder
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $formData->getCampus());
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();

    }
}
