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
     * Generate and return C headers
     */
    public function getCHeaderCode(){
        return "
#include <stdio.h>
#include <stdlib.h>
#include <getopt.h>

";
    }
    
    /**
     * Generate and return help function in C 
     */
    public function getCHelpCode(){
        $result='';
        
        $result.='// Display help information
void help(){
        printf("'.$this->slug.' - '.$this->description.'\n");
	printf("Options:\n");';
        
        foreach($this->projectOptions as $option){
            $result.='
        printf("-'.$option->getShortName().' or --'.$option->getLongName().': '.$option->getDescription().'\n");';
            
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
// Here your var definition

// GetOpt definition
';
        foreach($this->projectOptions as $option){
            if($option->getShortname()=='h' && $option->getLongname()=='help'){
                // Do nothing
            }else{
                if($option->getArguments()==true){
                    $result.='char *opt_'.$option->getLongname().';
    ';
                }else{
                    $result.='char opt_'.$option->getLongname().'=0;
    ';
                }
            }
        }        
$result.='
int next_option;
const char* const short_options = "';
        foreach($this->projectOptions as $option){
            $result.=$option->getShortname().($option->getArguments()==true?':':'');
        }
        $result.='" ;
const struct option long_options[] =
{
';
        foreach($this->projectOptions as $option){
            $result.='  { "'.$option->getLongname().'", '.($option->getArguments()==true?'1':'0').', NULL, \''.$option->getShortname().'\' },
';
        }
        $result.='        
  { NULL, 0, NULL, 0 }
};
// Init function
int main(int argc, char *argv[]){
while (1)
        {
        // Obtain a option
        next_option = getopt_long (argc, argv, short_options, long_options, NULL);

        if (next_option == -1)
          break; // No more options. Break loop.

        switch (next_option)
        {
';
        foreach($this->projectOptions as $option){
            if($option->getShortname()=='h' && $option->getLongname()=='help'){
                $result.='
          case \'h\' : // -h or --help 
            help();
            return(1);';              
            }else{
            $result.='
          case \''.$option->getShortname().'\' : // -'.$option->getShortname().' or --'.$option->getLongname().'
            opt_'.$option->getLongname().'=optarg;
            break;';
            }
        }

         $result.='

          case \'?\' : // Invalid option
              '.($this->hasHelp()?'help(); // Return help':'').'
              return(1);

          case -1 : // No more options
              break;

          default : // Something unexpected? Aborting
              return(1);
          }
        }
        
        // Iterate over rest arguments called argv[optind]
        while (optind < argc){
            // Your code here 

            optind++;
        }
        
}';
        
    /*
     * { "help",         0,  NULL,   \'h\},
{ "ph",           1,  NULL,   \'p\'},
{ "first",        1,  NULL,   \'f\'},
{ "last",         1,  NULL,   \'l\'},
     */    
        return $result;
    }   
    
    
    
    /**
     * Call al function to generate C sources
     */
    public function getCSource(){
        return $this->getCHeaderCode().
($this->hasHelp()?$this->getCHelpCode():'').'
'.$this->getCMainCode();
    }
    
}