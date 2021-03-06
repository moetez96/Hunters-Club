<?php

namespace JournaleBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Journale
 *
 * @ORM\Table(name="journale")
 * @ORM\Entity(repositoryClass="JournaleBundle\Repository\JournaleRepository")
 */
class Journale
{
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
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
     * @ORM\Column(name="nbchasse", type="integer")
     *
     * @Assert\NotNull(message="Champ Vide*")
     * @Assert\Range(
     *      min = 1,
     *      max = 20,
     *      minMessage = "au moins {{ limit }}",
     *      maxMessage = "Max valeur {{ limit }}"
     * )
     */
    private $nbchasse;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotNull(message="Champ Vide*")
     * /**
     * @Assert\Range(
     *     min = "first day of January",
     *     max = "first day of January next year"
     * )
     */

    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     *
     *
     *
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="idchasseur", type="string", length=255)
     *
     *
     */
    private $idchasseur;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * ORM\JoinColumn(name="user_id",refrencedColumnName="id")
     */
    protected $user;
    /**
     * @ORM\ManyToOne(targetEntity="EvenementBundle\Entity\Evenement")
     * ORM\JoinColumn(name="evenement_id",refrencedColumnName="id")
     */
    protected $evenement;
    /**
     * @ORM\ManyToOne(targetEntity="chasseBundle\Entity\annimal")
     * ORM\JoinColumn(name="annimal",refrencedColumnName="id")
     */
    protected $annimal;
    /**
     * @ORM\ManyToOne(targetEntity="chasseBundle\Entity\lieu")
     * ORM\JoinColumn(name="lieu",refrencedColumnName="id")
     */
    protected $lieu;


    /**
     * @return mixed
     */

    /**
     * @ORM\Column(type="string")
     *
     *
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;
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

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set animal
     *
     * @param string $animal
     *
     * @return Journale
     */
    public function setAnimal($animal)
    {
        $this->animal = $animal;

        return $this;
    }

    /**
     * Get animal
     *
     * @return string
     */
    public function getAnimal()
    {
        return $this->animal;
    }

    /**
     * Set nbchasse
     *
     * @param integer $nbchasse
     *
     * @return Journale
     */
    public function setNbchasse($nbchasse)
    {
        $this->nbchasse = $nbchasse;

        return $this;
    }

    /**
     * Get nbchasse
     *
     * @return int
     */
    public function getNbchasse()
    {
        return $this->nbchasse;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Journale
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Journale
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
     * Set description
     *
     * @param string $description
     *
     * @return Journale
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
     * Set idchasseur
     *
     * @param string $idchasseur
     *
     * @return Journale
     */
    public function setIdchasseur($idchasseur)
    {
        $this->idchasseur = $idchasseur;

        return $this;
    }

    /**
     * Get idchasseur
     *
     * @return string
     */
    public function getIdchasseur()
    {
        return $this->idchasseur;
    }


    /**
     * Set evenement
     *
     * @param \EvenementBundle\Entity\Evenement $evenement
     *
     * @return Journale
     */
    public function setEvenement(\EvenementBundle\Entity\Evenement $evenement = null)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \EvenementBundle\Entity\Evenement
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * Set annimal
     *
     * @param \chasseBundle\Entity\annimal $annimal
     *
     * @return Journale
     */
    public function setAnnimal(\chasseBundle\Entity\annimal $annimal = null)
    {
        $this->annimal = $annimal;

        return $this;
    }

    /**
     * Get annimal
     *
     * @return \chasseBundle\Entity\annimal
     */
    public function getAnnimal()
    {
        return $this->annimal;
    }
}
