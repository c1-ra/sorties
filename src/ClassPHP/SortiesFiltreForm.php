<?php

namespace App\ClassPHP;

use App\Entity\Campus;

class SortiesFiltreForm
{
    private ?Campus $campus = null;
    private ?string $rechercheNom = null;
    private ?\DateTime $dateMin = null;
    private ?\DateTime $dateMax = null;

    private bool $orga = false;
    private bool $inscrit = false;
    private bool $nonInscrit = false;
    private bool $passees = false;

    /**
     * @return Campus
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    public function getRechercheNom()
    {
        return $this->rechercheNom;
    }

    public function setRechercheNom($rechercheNom): void
    {
        $this->rechercheNom = $rechercheNom;
    }


    public function getDateMin()
    {
        return $this->dateMin;
    }


    public function setDateMin($dateMin): void
    {
        $this->dateMin = $dateMin;
    }


    public function getDateMax()
    {
        return $this->dateMax;
    }


    public function setDateMax($dateMax)
    {
        $this->dateMax = $dateMax;
    }

    public function getFiltres()
    {
        return $this->filtres;
    }

    public function setFiltres($tableau): void
    {
        $this->filtres[] = array();
        $this->filtres[] = $tableau;
    }

    public function setFiltre(string $clef, bool $valeur): void
    {
        $this->filtres[$clef] = $valeur;
    }

    /**
     * @return bool
     */
    public function isOrga(): bool
    {
        return $this->orga;
    }

    /**
     * @param bool $orga
     */
    public function setOrga(bool $orga): void
    {
        $this->orga = $orga;
    }

    /**
     * @return bool
     */
    public function isInscrit(): bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool $inscrit
     */
    public function setInscrit(bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return bool
     */
    public function isNonInscrit(): bool
    {
        return $this->nonInscrit;
    }

    /**
     * @param bool $nonInscrit
     */
    public function setNonInscrit(bool $nonInscrit): void
    {
        $this->nonInscrit = $nonInscrit;
    }

    /**
     * @return bool
     */
    public function isPassees(): bool
    {
        return $this->passees;
    }

    /**
     * @param bool $passees
     */
    public function setPassees(bool $passees): void
    {
        $this->passees = $passees;
    }

}
