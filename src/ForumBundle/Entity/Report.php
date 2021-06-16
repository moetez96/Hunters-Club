<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\ReportRepository")
 */
class Report
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
     *
     * @ORM\ManyToOne(targetEntity="Question1")
     * @ORM\JoinColumn(name="quest_id",referencedColumnName="id",onDelete="CASCADE")
     */

    private $question;

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
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
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
     * Set nbrepo
     *
     * @param integer $nbrepo
     *
     * @return Report
     */
    public function setNbrepo($nbrepo)
    {
        $this->nbrepo = $nbrepo;

        return $this;
    }

    /**
     * Get nbrepo
     *
     * @return int
     */
    public function getNbrepo()
    {
        return $this->nbrepo;
    }
}

