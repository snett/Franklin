<?php

/**
 * XML Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\IO;

define('XML_MODE_FILE', 1);
define('XML_MODE_STRING', 2);

class XML {
    
    public $XML;
    private $Mode;
    
    public function __construct($StringOrFile, $Mode=XML_MODE_FILE) {
        $this->Mode = $Mode;
        if ($this->Mode == XML_MODE_STRING){
            $this->loadString($StringOrFile);
        } else{
            $this->loadFile($StringOrFile);
        }
    }
    
    public function __toString() {
        return((string)$this->XML->asXML());
    }
    
    public function toArray() {
        $Array = array();
        foreach ($this->XML as $ElementKey => $Element) {
            $Array[(string)$ElementKey] = (string) $Element;
        }
        return($Array);
    }
    
    public function setMode($Mode=XML_MODE_FILE) {
        $this->Mode = $Mode;
    }
    
    public function loadFile($File) {
        $this->XML = simplexml_load_file($File);
    }
    
    public function loadString($String) {
        $this->XML = simplexml_load_string($String);
    }
    
    public function loadJSON($JSON) {
        $JSON = new JSON();
        $this->XML = $this->loadString($JSON->Decode($JSON));
    }
    

}
