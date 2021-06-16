<?php

namespace JournaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ompetition
 *
 * @ORM\Table(name="ompetition")
 * @ORM\Entity(repositoryClass="JournaleBundle\Repository\ompetitionRepository")
 */
class ompetition
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

