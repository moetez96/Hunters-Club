<?php

namespace chasseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="chasseBundle\Repository\ratingRepository")
 */
class rating
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
     * @var integer
     * @ORM\Column(name="star", type="integer" )
     */
    public $star;



    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="idu",referencedColumnName="id" , onDelete="CASCADE")
     */
    private $user;
    /**
     *
     * @ORM\ManyToOne(targetEntity="saison")
     * @ORM\JoinColumn(name="idS",referencedColumnName="id" , onDelete="CASCADE")
     */
    private $saison;

    /**
     * @return mixed
     */
    public function getSaison()
    {
        return $this->saison;
    }

    /**
     * @param mixed $saison
     */
    public function setSaison($saison)
    {
        $this->saison = $saison;
    }



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
     * @return int
     */
    public function getStar()
    {
        return $this->star;
    }

    /**
     * @param int $star
     */
    public function setStar($star)
    {
        $this->star = $star;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}

