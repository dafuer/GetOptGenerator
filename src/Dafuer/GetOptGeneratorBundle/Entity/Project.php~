<?php

namespace Dafuer\GetOptGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dafuer\GetOptGeneratorBundle\Util\Util;
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
     * @ORM\ManyToOne(targetEntity="Dafuer\GetOptGeneratorBundle\Entity\User", inversedBy="projects", cascade={"persist"}) 
     */
    private $user;
    
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
     * @ORM\OneToMany(targetEntity="ProjectOption", mappedBy="project", cascade={"persist"} )
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
     * Generate and return help function in C 
     */
    public function getCHelpCode(){
        $result='';
        
        $result.='
// Display help information
void help(){
        printf("'.$this->slug.' - '.$this->description.'\n");
	printf("Options:\n\n");';
        
        foreach($this->projectOptions as $option){
            $result.='
        printf("-'.$option->getShortName().' --'.$option->getLongName().': '.$option->getDescription().'\n");';
            
        }
        $result.='
}';
        
 
        
        return $result;
    }
    
    
    /**
     * Generateand return main function 
     */
     public function getCMainCode(){
        $result='';
        
        $result.='
// Init function
int main(int argc, char *argv[]){
        read_options(argc,argv);
}';
        
        
        return $result;
    }   
    
    
    /**
     * Generated global variables
     */
     public function getCGlobalVariablesCode(){
        $result='';

        
        foreach($this->projectOptions as $option){
            $result.='
// Define getopt parameters like a vars
char opt_'.Util::getSlug($option->getLongName()).' = 0;';
            
        }

 
        
        return $result;
    }
    
    
    /**
     * Generated global variables
     */
     public function getReadOptionsCode(){
        $result='';

        
        foreach($this->projectOptions as $option){
            $result.='
// This function read options and set up parameters
void read_options(int argc, char *argv[]){
int next_option;
';
            
        }
        
        $result.='
}';
 
        
        return $result;
    }
    
    
    /**
     * Call al function to generate C sources
     */
    public function getCSource(){
        return $this->getCGlobalVariablesCode().'
'.$this->getCHelpCode().'
'.$this->getReadOptionsCode().'
'.$this->getCMainCode();
    }
    
}

