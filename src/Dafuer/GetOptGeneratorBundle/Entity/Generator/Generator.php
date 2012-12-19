<?php

namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Project;

abstract class Generator {
    protected $project;
    
    /**
     * Valid languajes
     */
    public static $LANGUAJES=array(
        'c'=>'C', 
        'bash'=>'Bash', 
        );
    
    /**
     * Return languaje id (ie 'c')
     */
    abstract public function getId();
    
    /**
     * Return languaje name (ie 'C')
     */
    abstract public function getName();
    
    /**
     * Return source code translating to a languaje
     */
    abstract public function getCode();
    
    /**
     * Return common extension of a source files in a languaje
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
