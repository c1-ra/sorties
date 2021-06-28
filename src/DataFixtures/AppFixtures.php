<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $campus = new Campus();
        $campus->setNom("campus1");
        $manager->persist($campus);

        $campus2 = new Campus();
        $campus2->setNom("campus2");
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom("campus3");
        $manager->persist($campus3);

        $participant = new Participant();
        $participant->setPseudo("pseudo1");
        $participant->setNom("nom1");
        $participant->setPrenom("prenom1");
        $participant->setTelephone("1111111111");
        $participant->setMail("mail1@mail.com");
        $participant->setRoles(['ROLE_USER']);
        $participant->setAdministrateur(0);
        $participant->setActif(0);
        $participant->setCampus($campus);
        $password = $this->encoder->encodePassword($participant, 'mdpmdp');
        $participant->setPassword($password);
        $manager->persist($participant);

        $participant2 = new Participant();
        $participant2->setPseudo("pseudo2");
        $participant2->setNom("nom2");
        $participant2->setPrenom("prenom2");
        $participant2->setTelephone("2222222222");
        $participant2->setMail("mail2@mail.com");
        $participant2->setRoles(['ROLE_USER']);
        $participant2->setAdministrateur(0);
        $participant2->setActif(0);
        $participant2->setCampus($campus2);
        $password = $this->encoder->encodePassword($participant2, 'mdpmdp');
        $participant2->setPassword($password);
        $manager->persist($participant2);

        $participant3 = new Participant();
        $participant3->setPseudo("pseudo3");
        $participant3->setNom("nom3");
        $participant3->setPrenom("prenom3");
        $participant3->setTelephone("3333333333");
        $participant3->setMail("mail3@mail.com");
        $participant3->setRoles(['ROLE_USER']);
        $participant3->setAdministrateur(0);
        $participant3->setActif(0);
        $participant3->setCampus($campus2);
        $password = $this->encoder->encodePassword($participant3, 'mdpmdp');
        $participant3->setPassword($password);
        $manager->persist($participant3);

        $ville1 = new Ville();
        $ville1->setNom("ville1");
        $ville1->setCodePostal("00001");
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom("ville2");
        $ville2->setCodePostal("00002");
        $manager->persist($ville2);

        $lieu1 = new Lieu();
        $lieu1->setNom("lieu1");
        $lieu1->setRue("1 rue Un");
        $lieu1->setLatitude(1);
        $lieu1->setLongitude(1);
        $lieu1->setVille($ville1);
        $manager->persist($lieu1);

        $lieu1b = new Lieu();
        $lieu1b->setNom("lieu1b");
        $lieu1b->setRue("1b rue Un");
        $lieu1b->setLatitude(111);
        $lieu1b->setLongitude(111);
        $lieu1b->setVille($ville1);
        $manager->persist($lieu1b);

        $lieu2 = new Lieu();
        $lieu2->setNom("lieu2");
        $lieu2 ->setRue("2 rue Deux");
        $lieu2->setLatitude(2);
        $lieu2->setLongitude(2);
        $lieu2->setVille($ville2);
        $manager->persist($lieu2);

        $lieu2b = new Lieu();
        $lieu2b->setNom("lieu2b");
        $lieu2b ->setRue("2b rue Deux");
        $lieu2b->setLatitude(222);
        $lieu2b->setLongitude(222);
        $lieu2b->setVille($ville2);
        $manager->persist($lieu2b);

        $etat1 = new Etat();
        $etat1->setLibelle("Créée");
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle("Ouverte");
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle("Clôturée");
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle("En cours");
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle("Passée");
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle("Annulée");
        $manager->persist($etat6);

        $sortie1 = new Sortie();
        $sortie1->setNom("sortie1");
        $sortie1->setDateHeureDebut(new \DateTime('10/11/2021 12:00:00'));
        $sortie1->setDuree(120);
        $sortie1->setDateLimiteInscription(new \DateTime('3/10/2021'));
        $sortie1->setNbInscriptionsMax(11);
        $sortie1->setInfosSortie('infos 1');
        $sortie1->setEtat($etat2);
        $sortie1->setOrganisateur($participant2);
        $sortie1->setCampus($campus);
        $sortie1->setLieu($lieu1);
        $sortie1->addParticipant($participant);
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setNom("sortie2");
        $sortie2->setDateHeureDebut(new \DateTime('12/11/2021 22:00:00'));
        $sortie2->setDuree(240);
        $sortie2->setDateLimiteInscription(new \DateTime('11/02/2021'));
        $sortie2->setNbInscriptionsMax(22);
        $sortie2->setInfosSortie('infos 2');
        $sortie2->setEtat($etat2);
        $sortie2->setOrganisateur($participant);
        $sortie2->setCampus($campus2);
        $sortie2->setLieu($lieu2);
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setNom("sortie3");
        $sortie3->setDateHeureDebut(new \DateTime('12/11/2021 22:00:33'));
        $sortie3->setDuree(340);
        $sortie3->setDateLimiteInscription(new \DateTime('3/11/2021'));
        $sortie3->setNbInscriptionsMax(33);
        $sortie3->setInfosSortie('infos 3');
        $sortie3->setEtat($etat2);
        $sortie3->addParticipant($participant2);
        $sortie3->setOrganisateur($participant);
        $sortie3->setCampus($campus);
        $sortie3->setLieu($lieu1);
        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setNom("sortie4");
        $sortie4->setDateHeureDebut(new \DateTime('05/14/2021 22:00:44'));
        $sortie4->setDuree(440);
        $sortie4->setDateLimiteInscription(new \DateTime('05/04/2021'));
        $sortie4->setNbInscriptionsMax(44);
        $sortie4->setInfosSortie('infos 4');
        $sortie4->setEtat($etat5);
        $sortie4->setOrganisateur($participant);
        $sortie4->setCampus($campus2);
        $sortie4->setLieu($lieu2);
        $manager->persist($sortie4);

        $sortie5 = new Sortie();
        $sortie5->setNom("sortie5");
        $sortie5->setDateHeureDebut(new \DateTime('11/15/2020 11:55:55'));
        $sortie5->setDuree(550);
        $sortie5->setDateLimiteInscription(new \DateTime('11/05/2020'));
        $sortie5->setNbInscriptionsMax(55);
        $sortie5->setInfosSortie('infos 5');
        $sortie5->setEtat($etat5);
        $sortie5->setOrganisateur($participant2);
        $sortie5->addParticipant($participant);
        $sortie5->setCampus($campus);
        $sortie5->setLieu($lieu1);
        $manager->persist($sortie5);

        $sortie6 = new Sortie();
        $sortie6->setNom("sortie6");
        $sortie6->setDateHeureDebut(new \DateTime('11/16/2020 11:56:56'));
        $sortie6->setDuree(660);
        $sortie6->setDateLimiteInscription(new \DateTime('11/06/2020'));
        $sortie6->setNbInscriptionsMax(66);
        $sortie6->setInfosSortie('infos 6');
        $sortie6->setEtat($etat5);
        $sortie6->setOrganisateur($participant);
        $sortie6->addParticipant($participant2);
        $sortie6->setCampus($campus2);
        $sortie6->setLieu($lieu1);
        $manager->persist($sortie6);

        $sortie7 = new Sortie();
        $sortie7->setNom("sortie7");
        $sortie7->setDateHeureDebut(new \DateTime('12/17/2021 11:57:57'));
        $sortie7->setDuree(770);
        $sortie7->setDateLimiteInscription(new \DateTime('12/07/2021'));
        $sortie7->setNbInscriptionsMax(77);
        $sortie7->setInfosSortie('infos 7');
        $sortie7->setEtat($etat2);
        $sortie7->setOrganisateur($participant);
        $sortie7->addParticipant($participant3);
        $sortie7->setCampus($campus2);
        $sortie7->setLieu($lieu1);
        $manager->persist($sortie7);

        $sortie8 = new Sortie();
        $sortie8->setNom("sortie8");
        $sortie8->setDateHeureDebut(new \DateTime('12/12/2021 23:00:00'));
        $sortie8->setDuree(890);
        $sortie8->setDateLimiteInscription(new \DateTime('12/01/2021'));
        $sortie8->setNbInscriptionsMax(88);
        $sortie8->setInfosSortie('infos 8');
        $sortie8->setEtat($etat2);
        $sortie8->setOrganisateur($participant2);
        $sortie8->setCampus($campus3);
        $sortie8->setLieu($lieu2);
        $sortie8->addParticipant($participant);
        $manager->persist($sortie8);

        $sortie9 = new Sortie();
        $sortie9->setNom("sortie9");
        $sortie9->setDateHeureDebut(new \DateTime('12/12/2021 23:00:00'));
        $sortie9->setDuree(9110);
        $sortie9->setDateLimiteInscription(new \DateTime('12/01/2021'));
        $sortie9->setNbInscriptionsMax(99);
        $sortie9->setInfosSortie('infos 9');
        $sortie9->setEtat($etat2);
        $sortie9->setOrganisateur($participant);
        $sortie9->setCampus($campus3);
        $sortie9->setLieu($lieu2);
        $manager->persist($sortie9);

        $sortie10 = new Sortie();
        $sortie10->setNom("sortie10");
        $sortie10->setDateHeureDebut(new \DateTime('08/04/2021 10:00:10'));
        $sortie10->setDuree(101);
        $sortie10->setDateLimiteInscription(new \DateTime('08/01/2021'));
        $sortie10->setNbInscriptionsMax(10);
        $sortie10->setInfosSortie('infos 10');
        $sortie10->setEtat($etat2);
        $sortie10->addParticipant($participant);
        $sortie10->addParticipant($participant2);
        $sortie10->setOrganisateur($participant3);
        $sortie10->setCampus($campus3);
        $sortie10->setLieu($lieu2);
        $manager->persist($sortie10);

        $sortie11 = new Sortie();
        $sortie11->setNom("sortie11");
        $sortie11->setDateHeureDebut(new \DateTime('08/08/2020 11:00:10'));
        $sortie11->setDuree(11110);
        $sortie11->setDateLimiteInscription(new \DateTime('08/05/2020'));
        $sortie11->setNbInscriptionsMax(1111);
        $sortie11->setInfosSortie('infos 11');
        $sortie11->setEtat($etat5);
        $sortie11->setOrganisateur($participant);
        $sortie11->setCampus($campus3);
        $sortie11->setLieu($lieu2);
        $manager->persist($sortie11);

        $sortie12 = new Sortie();
        $sortie12->setNom("sortie12");
        $sortie12->setDateHeureDebut(new \DateTime('06/12/2021 11:00:10'));
        $sortie12->setDuree(120);
        $sortie12->setDateLimiteInscription(new \DateTime('06/10/2021'));
        $sortie12->setNbInscriptionsMax(12);
        $sortie12->setInfosSortie('infos 12');
        $sortie12->setEtat($etat5);
        $sortie12->setOrganisateur($participant);
        $sortie12->setCampus($campus2);
        $sortie12->setLieu($lieu2);
        $manager->persist($sortie12);

        $manager->flush();
    }
}
