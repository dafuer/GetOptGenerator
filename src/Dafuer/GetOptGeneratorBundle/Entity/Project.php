<?php

namespace Dafuer\GetOptGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dafuer\GetOptGeneratorBundle\Util\Util;
use Dafuer\GetOptGeneratorBundle\Entity\Generator\Generator;
/**
 * Dafuer\GetOptGeneratorBundle\Entity\Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Dafuer\GetOptGeneratorBundle\Entity\ProjectRepository")
 */
class Project {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column( type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column( type="string", length=255)
     */
    private $slug;
    
    /**
     * @var string $description
     *
     * @ORM\Column( type="string", length=255)
     */
    private $description;
    
    
    /**
     * @var  string $type
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $languaje; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Dafuer\GetOptGeneratorBundle\Entity\User", inversedBy="projects", cascade={"persist"}) 
     */
    private $user;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;


    
    /**
     * Return true if the project has two o more options with same short name.
     */
    public function isRepeatedShortOption(){
        $options=$this->getProjectOptions();
        $key_options=array();
        foreach($options as $option){
            $key_options[$option->getShortName()]=1;
        }
        
        if (count($options)==count($key_options)){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * Return true if the project has two o more options with same long name.
     */
    public function isRepeatedLongOption(){
        $options=$this->getProjectOptions();
        $key_options=array();
        foreach($options as $option){
            $key_options[$option->getLongName()]=1;
        }
        
        if (count($options)==count($key_options)){
            return false;
        }else{
            return true;
        }
    }    
    
    public function isInvalidNames(){
        $options=$this->getProjectOptions();
        foreach($options as $option){
            if((is_null($option->getLongName()) || $option->getLongName()=="") &&
               (is_null($option->getShortName()) || $option->getShortName()=="")){
                return true;
            }
        }
         
        return false;     
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    public function setId($id){
        $this->id=$id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name) {
        $this->name = $name;
        $this->slug = Util::getSlug($name);
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @ORM\OneToMany(targetEntity="ProjectOption", mappedBy="project", cascade={"persist","remove"} )
     */
    protected $projectOptions;

    public function convert() {
        
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->projectOptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created = $this->updated = new \DateTime("now");
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updated()
    {
        $this->updated = new \DateTime("now");
    }    

    /**
     * Add projectOptions
     *
     * @param Dafuer\GetOptGeneratorBundle\Entity\ProjectOption $projectOptions
     * @return Project
     */
    public function addProjectOption(\Dafuer\GetOptGeneratorBundle\Entity\ProjectOption $projectOptions) {
        $this->projectOptions[] = $projectOptions;

        return $this;
    }

    /**
     * Remove projectOptions
     *
     * @param Dafuer\GetOptGeneratorBundle\Entity\ProjectOption $projectOptions
     */
    public function removeProjectOption(\Dafuer\GetOptGeneratorBundle\Entity\ProjectOption $projectOptions) {
        $this->projectOptions->removeElement($projectOptions);
    }

    /**
     * Get projectOptions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProjectOptions() {
        return $this->projectOptions;
    }

    /**
     * 
     */
    public function __toString() {
        return $this->getName();
    }



    /**
     * Set user
     *
     * @param Dafuer\GetOptGeneratorBundle\Entity\User $user
     * @return Project
     */
    public function setUser(\Dafuer\GetOptGeneratorBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Dafuer\GetOptGeneratorBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Is this project using a help description?
     * @return boolean
     */
    public function hasHelp(){
        foreach($this->getProjectOptions() as $option){
            if($option->getShortname()=='h' && $option->getLongname()=='help') return true;
        }
        
        return false;
    }
    
    /**
     * Are there mandatory arguments in this project?
     * @return boolean
     */
    public function hasMandatoryOptions(){
        foreach($this->getProjectOptions() as $option){
            if($option->getMandatory()==true) return true;
        }
        
        return false;
    }    
    protected $generator=null;
    
    public function setGenerator(Generator $generator){
        $this->generator=$generator;
        $generator->setProject($this);
    }
    
    public function getGenerator(){
        return $this->generator;
    }
    
    /*
    public function getCode(){
        if($this->generator==null){
            return "";
        }else{
            return $this->generator->getCode($this);
        }
    }*/   

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Project
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Project
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set languaje
     *
     * @param string $languaje
     * @return Project
     */
    public function setLanguaje($languaje)
    {
        if (!in_array($languaje, array_keys($this::$LANGUAJE_VALUES))) {
            throw new \InvalidArgumentException("Invalid languaje");
        }
        
        $this->languaje = $languaje;
    
        return $this;
    }

    /**
     * Get languaje
     *
     * @return string 
     */
    public function getLanguaje()
    {
        return $this->languaje;
    }
    
    public static function getValidLanguajes(){
            return Generator::$LANGUAJES;
    }
}