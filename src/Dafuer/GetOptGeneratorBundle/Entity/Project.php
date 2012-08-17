<?php

namespace Dafuer\GetOptGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dafuer\GetOptGeneratorBundle\Entity\Project
 *
 * @ORM\Table()
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
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name) {
        $this->name = $name;

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
     * @ORM\OneToMany(targetEntity="ProjectOption", mappedBy="project")
     */
    protected $projectOptions;

    public function convert() {
        
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->projectOptions = new \Doctrine\Common\Collections\ArrayCollection();
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


    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}