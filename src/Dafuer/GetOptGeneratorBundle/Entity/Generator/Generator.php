<?php

namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Project;

abstract class Generator {
    protected $project;
    
    /**
     * Valid languages
     */
    public static $LANGUAGES=array(
        'c'=>'C', 
        'bash'=>'Bash', 
        );
    
    /**
     * Return language id (ie 'c')
     */
    abstract public function getId();
    
    /**
     * Return language name (ie 'C')
     */
    abstract public function getName();
    
    /**
     * Return source code translating to a language
     */
    abstract public function getCode();
    
    /**
     * Return common extension of a source files in a language
     */
    abstract public function getExtension();
    
    /**
     * Set project
     * @param \Dafuer\GetOptGeneratorBundle\Entity\Project $x
     */
    public function setProject(Project $x){
        $this->project=$x;
    }
    
    
    
}

?>
