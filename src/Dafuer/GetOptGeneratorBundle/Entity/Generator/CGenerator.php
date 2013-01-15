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
        return $this->getHeaderCode($this->project).
($this->project->hasHelp()?$this->getHelpCode($this->project):'').'
'.$this->getMainCode($this->project);
    }       

    /**
     * Generate and return C headers
     */
    private function getHeaderCode(){
        return "#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <getopt.h>

";
    }
    
    /**
     * Generate and return help function in C 
     */
    private function getHelpCode(){
        $result='';
        
        $result.='// Display help information
void help(){
    printf("'.$this->project->getSlug().' - '.$this->project->getDescription().'\n\n");
    printf("Usage example:\n");
    printf("'.$this->project->getSlug();
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
    $result.='\n\n");
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
            if($option->getArguments()==1 && $option->getType()!='undefined'){
                $result.=' '.$option->getType();
            }
            if($option->getDescription()!=null){
                $result.=': '.$option->getDescription().'.';
            }
            if($option->getMandatory()==1){
                $result.=' Required.';
            }
            
            $result.='\n");';
            
        }
        $result.='
    exit(1);
}';
        
 
        
        return $result;
    }
    
    
    /**
     * Generateand return main function 
     */
     private function getMainCode(){
        $result='
int main(int argc, char *argv[]){';
        
        $result.='
    // Here your var definition
    
    // Here flags (options without arguments) and arguments with defined type
';
    // Find if there are date or datetime types
    foreach($this->project->getProjectOptions() as $option){ 
        if($option->getArguments()==1 && ($option->getType()=='date' || $option->getType()=='datetime')){
            $result.='    char tmpchar[25];
';
            break;
        }
    }   
    // Active flags (options witouth arguments)
    foreach($this->project->getProjectOptions() as $option){
        if ($option->getShortName()=='h' && $this->project->hasHelp()){
            
        }else{
            if($option->getArguments()==0){
                $result.='    char '.$option->getOptionName().'=0;
';
            }
            if($option->getArguments()==1 && $option->getType()!='undefined'){
                $result.='    ';
                switch ($option->getType()){
                    case "integer":
                        $result.='int ';
                        break;
                    case "double":
                        $result.='double ';
                        break;
                    case "char":
                        $result.='char ';
                        break;
                    case "boolean":
                        $result.='char ';
                        break;
                    case "string":
                        $result.='char *';
                        break;
                   case "date":
                        $result.='struct tm *';
                        break;
                   case "datetime":
                        $result.='struct tm *';
                        break;
                }
                $result.=$option->getOptionName().';
';            
            }
        }
    }
    $result.='
    // GetOpt option definition
';
        foreach($this->project->getProjectOptions() as $option){
            if($option->getShortName()=='h' && $option->getLongName()=='help'){
                // Do nothing
            }else{
                if($option->getArguments()==true){
                    $result.='    char *opt_'.$option->getOptionName().'=0;
';
                }else{
//                    $result.='    char opt_'.$option->getOptionName().'=0;
//';
                }
            }
        }        
$result.='
    int next_option;
    const char* const short_options = "';
        foreach($this->project->getProjectOptions() as $option){
            if($option->getShortName()!=null){
                $result.=$option->getShortName().($option->getArguments()==true?':':'');
            }
        }
        $result.='" ;
    const struct option long_options[] =
        {
';
        $count=1;
        foreach($this->project->getProjectOptions() as $option){
            $result.='            { "'.$option->getLongName().'", '.($option->getArguments()==true?'1':'0').', NULL, '.(!is_null($option->getShortName())?"'".$option->getShortName()."'":$count).' },
';
            $count++;
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
        $count=1;
        foreach($this->project->getProjectOptions() as $option){
            if($option->getShortName()=='h' && $this->project->hasHelp()==true){
                $result.='
            case \'h\' : // -h or --help 
                help();
                break;
';              
            }else{
            $result.='
            case '.(!is_null($option->getShortName())?"'".$option->getShortName()."'":$count).' : // ';
                if($option->getShortName()!==null){
                    $result.='-'.$option->getShortName();
                }
                if($option->getLongName()!==null){
                    if($option->getShortName()!==null){
                        $result.=' or ';
                    }
                    $result.='--'.$option->getLongName();
                } 
                if($option->getArguments()==1){
                    $result.='
                opt_'.$option->getOptionName().'=optarg;
';
                }else{
                    $result.='
                '.$option->getOptionName().'=1;
';                    
                }
                if($option->getArguments()==1 && $option->getType()!='undefined'){
                    $result.='                ';
                    switch ($option->getType()){
                        case "integer":
                            $result.=$option->getOptionName().'=atoi(opt_'.$option->getOptionName().');';
                            break;
                        case "double":
                            $result.=$option->getOptionName().'=atof(opt_'.$option->getOptionName().');';
                            break;
                        case "char":
$result.='if(strlen(opt_'.$option->getOptionName().')!=1){
                    printf("'.$option->getOptionName().' must be a one character\n");
                    exit(-1); 
                }
                ';                            
                            $result.=$option->getOptionName().'=opt_'.$option->getOptionName().'[0];';
                            break;
                        case "boolean":
                            $result.='if(strcmp(opt_'.$option->getOptionName().',"true")==0 || atoi(opt_'.$option->getOptionName().')==1){
                    '.$option->getOptionName().'=1;
                }else if(strcmp(opt_'.$option->getOptionName().',"false")==0 || atoi(opt_'.$option->getOptionName().')==0){
                    '.$option->getOptionName().'=0;
                }else {
                    printf("'.$option->getOptionName().' is not a valid boolean (0,1,true or false are acepted)\n");
                    exit(-1);                    
                }';
                            break;
                        case "string":
                            $result.=$option->getOptionName().'=(char *)malloc(sizeof(char) * (strlen(opt_'.$option->getOptionName().')+1));
                strcpy('.$option->getOptionName().',opt_'.$option->getOptionName().');';
                            break;
                       case "date":
                            $result.='if (strlen(opt_'.$option->getOptionName().')<10 || opt_'.$option->getOptionName().'[4]!=opt_'.$option->getOptionName().'[7] || (opt_'.$option->getOptionName().'[4]!=\'-\' && opt_'.$option->getOptionName().'[4]!=\'/\') || (opt_'.$option->getOptionName().'[7]!=\'-\'  && opt_'.$option->getOptionName().'[7]!=\'/\')){
                    printf("'.$option->getOptionName().' is not a valid date (format: yyyy-mm-dd)\n");
                    exit(-1);
                }
                '.$option->getOptionName().'=(struct tm *)malloc(sizeof(struct tm));
                ';
                $result.="sprintf(tmpchar,\"%%d%c%%d%c%%d\", opt_".$option->getOptionName()."[4], opt_".$option->getOptionName()."[4]);";
                $result.='
                sscanf( opt_'.$option->getOptionName().', tmpchar, &'.$option->getOptionName().'->tm_year,&'.$option->getOptionName().'->tm_mon, &'.$option->getOptionName().'->tm_mday);
                '.$option->getOptionName().'->tm_mon='.$option->getOptionName().'->tm_mon-1;
                '.$option->getOptionName().'->tm_year='.$option->getOptionName().'->tm_year-1900;
                '.$option->getOptionName().'->tm_hour=0;
                '.$option->getOptionName().'->tm_min=0;
                '.$option->getOptionName().'->tm_sec=0;';
                            break;
                       case "datetime":
                            $result.='if (strlen(opt_'.$option->getOptionName().')<19 || opt_'.$option->getOptionName().'[4]!=opt_'.$option->getOptionName().'[7] || opt_'.$option->getOptionName().'[13]!=opt_'.$option->getOptionName().'[16] || (opt_'.$option->getOptionName().'[4]!=\'-\' && opt_'.$option->getOptionName().'[4]!=\'-\') || (opt_'.$option->getOptionName().'[7]!=\'-\' && opt_'.$option->getOptionName().'[7]!=\'/\') || (opt_'.$option->getOptionName().'[13]!=\':\' && opt_'.$option->getOptionName().'[13]!=\'.\') || (opt_'.$option->getOptionName().'[16]!=\':\' && opt_'.$option->getOptionName().'[16]!=\'.\')){
                    printf("'.$option->getOptionName().' is not a valid datetime (format: yyyy-mm-dd hh:mm:ss)\n");
                    exit(-1);
                }
                '.$option->getOptionName().'=(struct tm *)malloc(sizeof(struct tm));
                ';
                $result.="sprintf(tmpchar,\"%%d%c%%d%c%%d %%d%c%%d%c%%d\", opt_".$option->getOptionName()."[4], opt_".$option->getOptionName()."[4], opt_".$option->getOptionName()."[13], opt_".$option->getOptionName()."[13]);";
                $result.='
                sscanf( opt_'.$option->getOptionName().', tmpchar, &'.$option->getOptionName().'->tm_year,&'.$option->getOptionName().'->tm_mon, &'.$option->getOptionName().'->tm_mday, &'.$option->getOptionName().'->tm_hour, &'.$option->getOptionName().'->tm_min, &'.$option->getOptionName().'->tm_sec);
                '.$option->getOptionName().'->tm_mon='.$option->getOptionName().'->tm_mon-1;
                '.$option->getOptionName().'->tm_year='.$option->getOptionName().'->tm_year-1900;';
                            break;
                    }
                    $result.='
';            
                }                
                
                $result.='                break;
';
            }
            $count++;
        }

         $result.='
            case \'?\' : // Invalid option
                '.($this->project->hasHelp()?'help(); // Return help':'').'

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
                  $result.='opt_'.$option->getOptionName().'==0';
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
         $result.='    }';
    }

    
   // Last, iterate over other values
   $result.='
        
    // Iterate over rest arguments called argv[optind]
    while (optind < argc){
        // Your code here 
        
        optind++;
    }

    return 0;
}';

        return $result;
    }   
    
    
    
 
}


