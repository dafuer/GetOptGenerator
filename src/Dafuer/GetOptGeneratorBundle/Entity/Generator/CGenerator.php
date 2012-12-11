<?php


namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Generator\GeneratorInterface;
use Dafuer\GetOptGeneratorBundle\Entity\Project;

class CGenerator  extends GeneratorInterface
{
   
    
    /**
     * Call al function to generate C sources
     */
    public function getCode(Project $project){
        return $this->getCHeaderCode($project).
($project->hasHelp()?$this->getCHelpCode($project):'').'
'.$this->getCMainCode($project);
    }       

    /**
     * Generate and return C headers
     */
    public function getCHeaderCode($project){
        return "
#include <stdio.h>
#include <stdlib.h>
#include <getopt.h>

";
    }
    
    /**
     * Generate and return help function in C 
     */
    public function getCHelpCode($project){
        $result='';
        
        $result.='// Display help information
void help(){
    printf("'.$project->getSlug().' - '.$project->getDescription().'\n");
    printf("Options:\n");';
        
        foreach($project->getProjectOptions() as $option){
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
     public function getCMainCode($project){
        $result='
int main(int argc, char *argv[]){';
        
        $result.='
    // Here your var definition

    // GetOpt option definition
';
        foreach($project->getProjectOptions() as $option){
            if($option->getShortname()=='h' && $option->getLongname()=='help'){
                // Do nothing
            }else{
                if($option->getArguments()==true){
                    $result.='    char *opt_'.$option->getLongname().'=0;
    ';
                }else{
                    $result.='    char opt_'.$option->getLongname().'=0;
    ';
                }
            }
        }        
$result.='
    int next_option;
    const char* const short_options = "';
        foreach($project->getProjectOptions() as $option){
            $result.=$option->getShortname().($option->getArguments()==true?':':'');
        }
        $result.='" ;
    const struct option long_options[] =
        {
';
        foreach($project->getProjectOptions() as $option){
            $result.='            { "'.$option->getLongname().'", '.($option->getArguments()==true?'1':'0').', NULL, \''.$option->getShortname().'\' },
';
        }
        $result.='        { NULL, 0, NULL, 0 }
        };

    // Parse options
    while (1) {
        // Obtain a option
        next_option = getopt_long (argc, argv, short_options, long_options, NULL);

        if (next_option == -1)
            break; // No more options. Break loop.

        switch (next_option){
';
        foreach($project->getProjectOptions() as $option){
            if($option->getShortname()=='h' && $option->getLongname()=='help'){
                $result.='
            case \'h\' : // -h or --help 
                help();
                return(1);
';              
            }else{
            $result.='
            case \''.$option->getShortname().'\' : // -'.$option->getShortname().' or --'.$option->getLongname().'
                opt_'.$option->getLongname().'=optarg;
                break;';
            }
        }

         $result.='

            case \'?\' : // Invalid option
                '.($project->hasHelp()?'help(); // Return help':'').'
                return(1);

            case -1 : // No more options
                break;

            default : // Something unexpected? Aborting
                return(1);
        }
    }';
   
   $mandatory_options=0;
   // Then, validate
   if($project->hasMandatoryOptions()){
          $result.='    
              
    // Check for mandatory arguments
    if( ';
        foreach($project->getProjectOptions() as $option){
            if($option->getMandatory()==true){
                  if($mandatory_options!=0)  $result.=' || ';        
                  $result.='opt_'.$option->getLongname().'==0';
                  $mandatory_options++;
            }            
        }
        $result.=' ){
        printf("Mandatory arguments not specified\n");
';
     if($project->hasHelp()){
         $result.='        help();
';
        
     }
         $result.='        exit(-1);
    }';
    }
   // Last, iterate over other values
   $result.='
        
    // Iterate over rest arguments called argv[optind]
    while (optind < argc){
        // Your code here 
        
        optind++;
    }
        
}';

        return $result;
    }   
    
    
    
 
}


