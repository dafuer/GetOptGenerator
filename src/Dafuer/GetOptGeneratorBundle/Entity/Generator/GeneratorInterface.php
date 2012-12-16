<?php

namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Project;

abstract class GeneratorInterface {
    /**
     * Return source code translating to a languaje
     */
    abstract public function getCode(Project $project);
    
    /**
     * Return common extension of a source files in a languaje
     */
    abstract public function getExtension();
}

?>
