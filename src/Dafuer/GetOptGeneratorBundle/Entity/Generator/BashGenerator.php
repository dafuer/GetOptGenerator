<?php

namespace Dafuer\GetOptGeneratorBundle\Entity\Generator;

use Dafuer\GetOptGeneratorBundle\Entity\Generator\Generator;
use Dafuer\GetOptGeneratorBundle\Entity\Project;

class BashGenerator extends Generator {

    public function getId() {
        return 'bash';
    }

    public function getName() {
        return 'Bash';
    }

    /**
     * Return a extension of bash script source file
     * @return string extension
     */
    public function getExtension() {
        return 'sh';
    }

    /**
     * Call al function to generate bash sources
     */
    public function getCode() {
        $result = "#!/bin/bash

".$this->getHelpCode()."
# Declare vars. Flags initalizing to 0.
";
foreach($this->project->getProjectOptions() as $option){
    if($option->getArguments()==0 && !($option->getShortName()=='h' && $this->project->hasHelp()==1)){
        $result.=$option->getOptionName()."=0;
";        
    }
}
        
$result.="
# Execute getopt
ARGS=$(getopt -o \"";
        foreach($this->project->getProjectOptions() as $option){
            if($option->getShortName()!=null){
                $result.=$option->getShortName();
                if($option->getArguments()==1){
                    $result.=':';
                }
            }
        }
        $result.="\" -l \"";

        $tmp=array();
        $opt="";
        foreach($this->project->getProjectOptions() as $option){
            if($option->getLongName()!=null){
                $opt=$option->getLongName();
                if($option->getArguments()==1){
                    $opt.=':';
                }
                $tmp[]=$opt;
            }
        }        
        
        $result.=implode(",",$tmp)."\" -n \"".$this->project->getName()."\" -- \"$@\");

#Bad arguments
if [ $? -ne 0 ];
then
    help;
fi

eval set -- \"\$ARGS\";

while true; do
    case \"$1\" in
";
        foreach($this->project->getProjectOptions() as $option){
            $result.="        ";
            if($option->getShortName()!=null){
                $result.='-'.$option->getShortName();
            }
            if($option->getLongName()!=null){
                if($option->getShortName()!=null){
                    $result.='|';
                }
                $result.='--'.$option->getLongName();
            }
            $result.=")
            shift;";
            if($option->getShortName()=='h' && $this->project->hasHelp()==true){
                $result.='
            help;';
            }else{
                if($option->getArguments()==1){
                    $result.="
                    if [ -n \"\$1\" ]; 
                    then
                        ".$option->getOptionName()."=\"\$1\";
                        shift;
                    fi";         
                }else{
                    $result.="
                    ".$option->getOptionName()."=\"1\";";
                }
            }
        $result.="
            ;;
";
        }
        
           $result.="
        --)
            shift;
            break;
            ;;
    esac
done

# Check required arguments
";
       
            
        foreach($this->project->getProjectOptions() as $option){
             if($option->getArguments()==1 && $option->getMandatory()==1){
                 $result.="if [ -z \"\$".$option->getOptionName()."\" ]
then
    echo \"".$option->getOptionName()." is required\";
    help;
fi
";

             }
        }
       

        return $result;
    }
    
    private function getHelpCode(){
        $result='# Define help function
function help(){
    echo "'.$this->project->getName().' - '.$this->project->getDescription().'";
    echo "Usage example:";
    echo "'.$this->project->getSlug();
    // First mandatory options
    foreach($this->project->getProjectOptions() as $option){
        if($option->getMandatory()==1){
           if($option->getShortName()!=null && $option->getLongName()!=null){
                       $result.=' (-'.$option->getShortName().'|--'.$option->getLongName().')';
           }else{
               if($option->getShortName()!=null){
                    $result.=' -'.$option->getShortName();
               }else{
                    $result.=' --'.$option->getLongName();
               }
           }   
           if($option->getArguments()==1){
               if($option->getType()!='undefined'){
                   $result.=' '.$option->getType();
               }else{
                   $result.=' value';
               }
           }
        }
    }    
    // Second, optional options
    foreach($this->project->getProjectOptions() as $option){
        if($option->getMandatory()==0){
           if($option->getShortName()!=null && $option->getLongName()!=null){
                       $result.=' [(-'.$option->getShortName().'|--'.$option->getLongName().')';
           }else{
               if($option->getShortName()!=null){
                    $result.=' [-'.$option->getShortName();
               }else{
                    $result.=' [--'.$option->getLongName();
               }
           }   
           if($option->getArguments()==1){
               if($option->getType()!='undefined'){
                   $result.=' '.$option->getType();
               }else{
                   $result.=' value';
               }
           }
           $result.=']';
        }
    }       
    $result.='";
    echo "Options:";';
        foreach($this->project->getProjectOptions() as $option){
            $result.='
    echo "';
            if($option->getShortName()!==null){
                $result.='-'.$option->getShortName();
            }
            if($option->getLongName()!==null){
                if($option->getShortName()!==null){
                    $result.=' or ';
                }
                $result.='--'.$option->getLongName();
            }
            if($option->getArguments()==1 && $option->getType()!='undefined'){
                $result.=' '.$option->getType();
            }
            if($option->getDescription()!=null){
                $result.=': '.$option->getDescription().'.';
            }
            if($option->getMandatory()==1){
                $result.=' Required.';
            }
            
            $result.='";';
            
        }    
    $result.='
    exit 1;
}
'; 

        return $result;
        
    }

}

