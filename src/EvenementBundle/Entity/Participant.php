<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\ParticipantRepository")
 */
class Participant
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
     * @ORM\ManyToOne(targetEntity="EvenementBundle\Entity\Evenement",inversedBy="Participant")
     * @ORM\JoinColumn(name="Evenement_id",referencedColumnName="id")
     */
    private $Evenement;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * ORM\JoinColumn(name="user",refrencedColumnName="id")
     */
    protected $user;

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
     * @param \EvenementBundle\Entity\Evenement $evenement
     *
     * @return Participant
     */
    public function setEvenement(\EvenementBundle\Entity\Evenement $evenement = null)
    {
        $this->Evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \EvenementBundle\Entity\Evenement
     */
    public function getEvenement()
    {
        return $this->Evenement;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Participant
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
