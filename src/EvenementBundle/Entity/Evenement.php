<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\EvenementRepository")
 * @UniqueEntity (fields="nom",
 * message="Le nom de l'evenement existe déja")
 */
class Evenement
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
     * @ORM\Column(name="ClientId", type="integer")
     */
    private $ClientId;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     *
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "le nom de l'événement doit comporter au moins 3 caractères",
     *      maxMessage = "le nom de l'événement ne doit pas dépasser les {{limit}} 50 caractères"
     *    
     * )
     ** @Assert\NotNull(message="Le nom del'evenement doit etre non null ")
     *
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     ** @Assert\NotNull(message="date doit etre non null ")
     *
     */
    private $date;

    

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     *@Assert\Type(
     *     type="string"
     *    
     * )
     ** @Assert\NotNull(message="Lieu doit etre non null ")
     *
     */
    private $lieu;

    /**
     * @var float
     *
     *@ORM\Column(name="Latitude", type="string")
     */
    private $Latitude;

    /**
     * @var float
     *
     *@ORM\Column(name="Longitude", type="float")
     */
    private $Longitude;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     ** @Assert\NotNull(message="Description doit etre non null ")
     *
     *@Assert\Type(
     *     type="string"
     *    
     * )
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Il faut choisir une image  ")
     * 
     *
     */
    private $nomImage;
    /**
     *
     *@ Assert\File(maxSize="500k")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="GroupBundle\Entity\Groups",inversedBy="Evenement")
     * @ORM\JoinColumn(name="groups",referencedColumnName="id")
     */
    private $groupe;
    /**
     * @ORM\OneToMany(targetEntity="EvenementBundle\Entity\Participant",mappedBy="Evenement")
     */
    private $Participant;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * ORM\JoinColumn(name="user",refrencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EvenementBundle\Entity\TypeEvents",inversedBy="Evenement")
     * @ORM\JoinColumn(name="Type",referencedColumnName="id")
     */
    private $TypeEvents;


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
     * @return Evenement
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Evenement
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
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Evenement
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
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
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

    public function getWebpath(){
       return null===$this->nomImage ? null :$this->getUploadDir().'/'.$this->nomImage;
    }
    protected function getUploadRootDir()
    {
        
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
     protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'image';
    }
    public function uploadProfilePicture(){
     

     if (null===$this->file){
        return;
     }
     if($this->id){
         $this->file->move($this->getUploadRootDir(),$this->file->getClientOriginalName());

     }else{
         $this->file->move($this->getUploadRootDir(),$this->file->getClientOriginalName());

     }
     $this->setNomInamage($this->file->getClientOriginalName());
    }
    /**
     * Set nomImage
     *
     * @param string $nomImage
     *
     * @return categorie
     */
    public function setNomInamage($nomImage)
    {
        $this->nomImage = $nomImage;

        return $this;
    }
     /**
     * Get nomImage
     *
     * @return string
     */
    public function getNomImage()
    {
        return $this->nomImage;
    }
    

    /**
     * Set groupe
     *
     * @param \GroupBundle\Entity\Groups $groupe
     *
     * @return Evenement
     */
    public function setGroupe(\GroupBundle\Entity\Groups $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \EvenementBundle\Entity\groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Evenement
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

    /**
     * Set employeeId
     *
     * @param integer $employeeId
     *
     * @return Evenement
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    /**
     * Get employeeId
     *
     * @return integer
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Participant = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add participant
     *
     * @param \EvenementBundle\Entity\Participant $participant
     *
     * @return Evenement
     */
    public function addParticipant(\EvenementBundle\Entity\Participant $participant)
    {
        $this->Participant[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param \EvenementBundle\Entity\Participant $participant
     */
    public function removeParticipant(\EvenementBundle\Entity\Participant $participant)
    {
        $this->Participant->removeElement($participant);
    }

    /**
     * Get participant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipant()
    {
        return $this->Participant;
    }

    /**
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Evenement
     */
    public function setClientId($clientId)
    {
        $this->ClientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->ClientId;
    }

    /**
     * Set typeEvents
     *
     * @param \EvenementBundle\Entity\TypeEvents $typeEvents
     *
     * @return Evenement
     */
    public function setTypeEvents(\EvenementBundle\Entity\TypeEvents $typeEvents = null)
    {
        $this->TypeEvents = $typeEvents;

        return $this;
    }

    /**
     * Get typeEvents
     *
     * @return \EvenementBundle\Entity\TypeEvents
     */
    public function getTypeEvents()
    {
        return $this->TypeEvents;
    }

    /**
     * Set nomImage
     *
     * @param string $nomImage
     *
     * @return Evenement
     */
    public function setNomImage($nomImage)
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Evenement
     */
    public function setLatitude($latitude)
    {
        $this->Latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->Latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Evenement
     */
    public function setLongitude($longitude)
    {
        $this->Longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->Longitude;
    }
}
