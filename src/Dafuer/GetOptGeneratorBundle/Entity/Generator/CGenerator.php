<?php


namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Generator\Generator;
use Dafuer\GetOptGeneratorBundle\Entity\Project;

class CGenerator  extends Generator
{
   
    
    public function getId(){
        return 'c';
    }
    
    
    public function getName(){
        return 'C';
    }
    
    /**
     * Return a extension of c source file
     * @return string extension
     */
    public function getExtension(){
        return 'c';
    }


    /**
     * Call al function to generate C sources
     */
    public function getCode(){
        return $this->getCHeaderCode($this->project).
($this->project->hasHelp()?$this->getCHelpCode($this->project):'').'
'.$this->getCMainCode($this->project);
    }       

    /**
     * Generate and return C headers
     */
    public function getCHeaderCode(){
        return "#include <stdio.h>
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
    printf("'.$this->project->getSlug().' - '.$this->project->getDescription().'\n");
    printf("Options:\n");';
        
        foreach($this->project->getProjectOptions() as $option){
            $result.='
    printf("';
            if($option->getShortName()!==null){
                $result.='-'.$option->getShortName();
            }
            if($option->getLongName()!==null){
                if($option->getShortName()!==null){
                    $result.=' or ';
                }
                $result.='--'.$option->getLongName();
            }
            if($option->getDescription()!=null){
                $result.=': '.$option->getDescription();
            }
            $result.='\n");';
            
        }
        $result.='
}';
        
 
        
        return $result;
    }
    
    
    /**
     * Generateand return main function 
     */
     public function getCMainCode(){
        $result='
int main(int argc, char *argv[]){';
        
        $result.='
    // Here your var definition

    // GetOpt option definition
';
        foreach($this->project->getProjectOptions() as $option){
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
        foreach($this->project->getProjectOptions() as $option){
            $result.=$option->getShortname().($option->getArguments()==true?':':'');
        }
        $result.='" ;
    const struct option long_options[] =
        {
';
        foreach($this->project->getProjectOptions() as $option){
            $result.='            { "'.$option->getLongname().'", '.($option->getArguments()==true?'1':'0').', NULL, \''.$option->getShortname().'\' },
';
        }
        $result.='            { NULL, 0, NULL, 0 }
        };

    // Parse options
    while (1) {
        // Obtain a option
        next_option = getopt_long (argc, argv, short_options, long_options, NULL);

        if (next_option == -1)
            break; // No more options. Break loop.

        switch (next_option){
';
        foreach($this->project->getProjectOptions() as $option){
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
                '.($this->project->hasHelp()?'help(); // Return help':'').'
                return(1);

            case -1 : // No more options
                break;

            default : // Something unexpected? Aborting
                return(1);
        }
    }';
   
   $mandatory_options=0;
   // Then, validate
   if($this->project->hasMandatoryOptions()){
          $result.='    
              
    // Check for mandatory arguments
    if( ';
        foreach($this->project->getProjectOptions() as $option){
            if($option->getMandatory()==true){
                  if($mandatory_options!=0)  $result.=' || ';        
                  $result.='opt_'.$option->getLongname().'==0';
                  $mandatory_options++;
            }            
        }
        $result.=' ){
        printf("Mandatory arguments not specified\n");
';
     if($this->project->hasHelp()){
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


