<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question1
 *
 * @ORM\Table(name="question1")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\Question1Repository")
 */
class Question1
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
     * @var int
     *
     * @ORM\Column(name="nbVue", type="integer", nullable=true)
     */
    private $nbVue;


    /**
     * @var int
     *
     * @ORM\Column(name="nbRep", type="integer", nullable=true)
     */
    private $nbRep;

    /**
     * @return int
     */
    public function getNbVue()
    {
        return $this->nbVue;
    }

    /**
     * @param int $nbVue
     */
    public function setNbVue($nbVue)
    {
        $this->nbVue = $nbVue;
    }

    /**
     * @return int
     */
    public function getNbRep()
    {
        return $this->nbRep;
    }

    /**
     * @param int $nbRep
     */
    public function setNbRep($nbRep)
    {
        $this->nbRep = $nbRep;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="quest", type="string", length=255)
     */
    private $quest;

    /**
     * @var int
     *
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=255)
     */
    private $descr;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * Set nbVue
     *
     * @param integer $nbVue
     *
     * @return Question1
     */
    public function setNbrVue($nbVue)
    {
        $this->nbVue = $nbVue;

        return $this;
    }

    /**
     * Get nbVue
     *
     * @return int
     */
    public function getNbrVue()
    {
        return $this->nbVue;
    }

    /**
     * Set quest
     *
     * @param string $quest
     *
     * @return Question1
     */
    public function setQuest($quest)
    {
        $this->quest = $quest;

        return $this;
    }

    /**
     * Get quest
     *
     * @return string
     */
    public function getQuest()
    {
        return $this->quest;
    }

    /**
     * Set vote
     *
     * @param integer $vote
     *
     * @return Question1
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return Question1
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Question1
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(name="categorie",referencedColumnName="id",onDelete="CASCADE")
     */

    private $categorie;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id",onDelete="CASCADE")
     */

    private $user;

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

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }


}

