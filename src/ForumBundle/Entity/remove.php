<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * remove
 *
 * @ORM\Table(name="remove")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\removeRepository")
 */
class remove
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
     * @ORM\Column(name="nbdelete", type="integer")
     */
    private $nbdelete;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbdelete
     *
     * @param integer $nbdelete
     *
     * @return remove
     */
    public function setNbdelete($nbdelete)
    {
        $this->nbdelete = $nbdelete;

        return $this;
    }

    /**
     * Get nbdelete
     *
     * @return int
     */
    public function getNbdelete()
    {
        return $this->nbdelete;
    }

}

