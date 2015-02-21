<?php

/**
 * Aliases Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\System;

class Aliases {
    
    public $System;
    
    public $Aliases;

    public function __construct($System) {
        $this->System = $System;
        $this->Aliases = array();
        //$this->Aliases['/Otthon'] = '/q=Home';
        //$AliasConfigFile = new \Franklin\IO\XML($this->System->ConfigFile['Aliases']);
        /*$Aliases = $AliasConfigFile->XML;
        foreach ($Aliases->Alias as $Alias){
            $this->Aliases[(string)$Alias->From] = (string)$Alias->To;
        }*/
        $AliasObject = new \Franklin\Data\Alias($this);
        $AliasObjects = $AliasObject->Ls("`Status`='1'");
        foreach ($AliasObjects as $Alias){
            $this->Aliases[(string)$Alias->From] = (string)$Alias->To;
        }
        $this->Apply();
    }
    
    public function Apply() {
        if (array_key_exists($this->System->URL->Query, $this->Aliases)){
            $this->System->URL = new URL($this->System, $this->System->URL->Scheme . '://' . $this->System->URL->Host . $this->Aliases[$this->System->URL->Query], URL_MODE_ALIAS);
        }
    }
    
}
