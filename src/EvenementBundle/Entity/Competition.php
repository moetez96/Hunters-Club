<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\CompetitionRepository")
 */
class Competition
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="EvenementBundle\Entity\Evenement",inversedBy="Competition")
     * @ORM\JoinColumn(name="Evenement_id",referencedColumnName="id")
     */
    private $Evenement;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set evenement
     *
     * @param \EvenemenBundle\Entity\Evenement $evenement
     *
     * @return Competition
     */
    public function setEvenement(\EvenemenBundle\Entity\Evenement $evenement = null)
    {
        $this->Evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \EvenemenBundle\Entity\Evenement
     */
    public function getEvenement()
    {
        return $this->Evenement;
    }
}
