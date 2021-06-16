<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\groupeRepository")
 */
class groupe
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return groupe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return groupe
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add event
     *
     * @param \EvenementBundle\Entity\Events $event
     *
     * @return groupe
     */
    public function addEvent(\EvenementBundle\Entity\Events $event)
    {
        $this->Events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \EvenementBundle\Entity\Events $event
     */
    public function removeEvent(\EvenementBundle\Entity\Events $event)
    {
        $this->Events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->Events;
    }

    /**
     * Add evenement
     *
     * @param \EvenementBundle\Entity\Evenement $evenement
     *
     * @return groupe
     */
    public function addEvenement(\EvenementBundle\Entity\Evenement $evenement)
    {
        $this->Evenement[] = $evenement;

        return $this;
    }

    /**
     * Remove evenement
     *
     * @param \EvenementBundle\Entity\Evenement $evenement
     */
    public function removeEvenement(\EvenementBundle\Entity\Evenement $evenement)
    {
        $this->Evenement->removeElement($evenement);
    }

    /**
     * Get evenement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvenement()
    {
        return $this->Evenement;
    }
}
