<?php

namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Project;

abstract class GeneratorInterface {
    abstract public function getCode(Project $project);
}

?>
