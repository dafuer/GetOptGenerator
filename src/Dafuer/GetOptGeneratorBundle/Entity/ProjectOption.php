<?php

namespace Dafuer\GetOptGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dafuer\GetOptGeneratorBundle\Entity\ProjectOption
 *
 * @ORM\Table(name="project_option")
 * @ORM\Entity(repositoryClass="Dafuer\GetOptGeneratorBundle\Entity\ProjectOptionRepository")
 */
class ProjectOption
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $ShortName
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $shortName;

    /**
     * @var string $LongName
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longName;

    /**
     * @var boolean $arguments
     *
     * @ORM\Column(type="boolean")
     */
    private $arguments;    
    
    /**
     * @var boolean $mandatory
     * 
     * @ORM\Column(type="boolean")
     */
    private $mandatory;
    
    
    public static $TYPE_VALUES=array(
        'undefined'=>'undefined', 
        'integer'=>'integer', 
        'double'=>'double', 
        'char'=>'char', 
        'boolean'=>'boolean', 
        'string'=>'string', 
        'date'=>'date', 
        'datetime'=>'datetime');
    /**
     * @var  string $type
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $type='undefined';
    
    /**
     * @var string $Description
     *
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $description="";
    
    /**
     * @ORM\ManyToOne(targetEntity="Dafuer\GetOptGeneratorBundle\Entity\Project", inversedBy="projectOptions", cascade={"persist","remove"} )
     */
    private $project;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return ProjectOption
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    
        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set longName
     *
     * @param string $longName
     * @return ProjectOption
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;
    
        return $this;
    }

    /**
     * Get longName
     *
     * @return string 
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ProjectOption
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
     * 
     */
    public function __toString(){
        return $this->getLongName();
    }
    
    

    /**
     * Set project
     *
     * @param Dafuer\GetOptGeneratorBundle\Entity\Project $project
     * @return ProjectOption
     */
    public function setProject(\Dafuer\GetOptGeneratorBundle\Entity\Project $project = null)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     *
     * @return Dafuer\GetOptGeneratorBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
    public function __construct()
    {
        $this->project = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add project
     *
     * @param Dafuer\GetOptGeneratorBundle\Entity\Project $project
     * @return ProjectOption
     */
    public function addProject(\Dafuer\GetOptGeneratorBundle\Entity\Project $project)
    {
        $this->project[] = $project;
        return $this;
    }

    /**
     * Remove project
     *
     * @param Dafuer\GetOptGeneratorBundle\Entity\Project $project
     */
    public function removeProject(\Dafuer\GetOptGeneratorBundle\Entity\Project $project)
    {
        $this->project->removeElement($project);
    }

    /**
     * Set arguments
     *
     * @param boolean $arguments
     * @return ProjectOption
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    
        return $this;
    }

    /**
     * Get arguments
     *
     * @return boolean 
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set mandatory
     *
     * @param boolean $mandatory
     * @return ProjectOption
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
    
        return $this;
    }

    /**
     * Get mandatory
     *
     * @return boolean 
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Set type
     *
     * @param \varchar $type
     * @return ProjectOption
     */
    public function setType( $type)
    {
        if (!in_array($type, array_keys($this::$TYPE_VALUES))) {
            throw new \InvalidArgumentException("Invalid type");
        }
        
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \varchar 
     */
    public function getType()
    {
        
        return $this->type;
    }
    
      
    
}