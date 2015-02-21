<?php

/**
 * System Main Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin;

class System{
    
    public $Language;
    public $DefaultLanguage;
    
    public $Directory;
    public $ConfigFile;
    
    public $Database;
    public $XML;
    
    public $Handler;
    public $Stream;
    
    public $Aliases;
    public $URL;
    public $Request;
    
    public $Application;
    public $Action;
    public $Routine;
    public $SubRoutine;
    
    public $OutType;
    
    public function __construct($DefaultLanguage, $RootDirectory="", $ConfigDir=null, $StructureDir=null) {
        /*Set system root*/
        $this->Directory['Root'] = $RootDirectory;
        
        /*System Default Language*/
        $this->DefaultLanguage = $DefaultLanguage;
        
        /*System Actual Language*/
        $this->Language = $Language;
        
        /*Define configuration files*/
        if ($ConfigDir !== null){
            $this->Directory['Config'] = $ConfigDir;
        } else{
            $this->Directory['Config'] = $this->Directory['Root'] . "Config/";
        }
        
        /*Define structure files*/
        if ($StructureDir !== null){
            $this->Directory['Structures'] = $StructureDir;
        } else{
            $this->Directory['Structures'] = $this->Directory['Root'] . "Structures/";
        }
        
        /*Init URL*/
        $this->URL = new System\URL($this);
        
        /*Init database*/
        $this->ConfigFile['Database'] = $this->Directory['Config'] . "Database.xml";
        $this->initDatabase();
        
        /*Init Aliases*/
        $this->ConfigFile['Aliases'] = $this->Directory['Config'] . "Aliases.xml";
        $this->Aliases = new System\Aliases($this);
        
        /*Set URL Params: Application, Action*/
        $this->URL->setParams();
        
        /*Init Request*/
        $this->Request = new IO\Request();
        
        switch ($this->Request->get("Format")) {
            case 'XML':
                $this->OutType = 'XML';
            break;
            case 'JSON':
                $this->OutType = 'JSON';
            case 'HTML':
            default:
                $this->OutType = 'HTML';
            break;
        }
        
        /*Init Session*/
        $this->Session = new IO\Session($this);
        
        /*Init Stream*/
        $this->Stream = new IO\Stream($this);
        
        /*Print out the default channel of the stream*/
        //$this->Stream->__print();
        
    }
    
    public function initDatabase() {
        $DbConfigFile = new IO\XML($this->ConfigFile['Database']);
        $DbConfig["server"] = (string) $DbConfigFile->XML->Server;
        $DbConfig["user"] = (string) $DbConfigFile->XML->User;
        $DbConfig["password"] = (string) $DbConfigFile->XML->Password;
        $DbConfig["database"] = (string) $DbConfigFile->XML->Database;
        $this->Database = new System\Database($DbConfig);
    }
    
    public function __toString() {
        return("#Franklin#");
    }
    
}
