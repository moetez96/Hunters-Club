<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table(name="membre")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\MembreRepository")
 */
class Membre
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateJoin", type="datetime")
     */
    private $dateJoin;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="groupe")
     * @ORM\JoinColumn(name="idG",referencedColumnName="id")
     */
    private $idG;
    
     /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="idM",referencedColumnName="id")
     */
    private $idM;

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
     * Set dateJoin
     *
     * @param \DateTime $dateJoin
     *
     * @return Membre
     */
    public function setDateJoin($dateJoin)
    {
        $this->dateJoin = $dateJoin;

        return $this;
    }

    /**
     * Get dateJoin
     *
     * @return \DateTime
     */
    public function getDateJoin()
    {
        return $this->dateJoin;
    }

    /**
     * Set idG
     *
     * @param \EvenementBundle\Entity\groupe $idG
     *
     * @return Membre
     */
    public function setIdG(\EvenementBundle\Entity\groupe $idG = null)
    {
        $this->idG = $idG;

        return $this;
    }

    /**
     * Get idG
     *
     * @return \EvenementBundle\Entity\groupe
     */
    public function getIdG()
    {
        return $this->idG;
    }

    /**
     * Set idM
     *
     * @param \AppBundle\Entity\User $idM
     *
     * @return Membre
     */
    public function setIdM(\AppBundle\Entity\User $idM = null)
    {
        $this->idM = $idM;

        return $this;
    }

    /**
     * Get idM
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdM()
    {
        return $this->idM;
    }
}
