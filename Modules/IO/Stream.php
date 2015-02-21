<?php

/**
 * Stream Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\IO;

class Stream {
    
    private $System;
    
    private $Channel;
    private $Stream;

    public function __construct($System, $Channel=0) {
        $this->System = $System;
        $this->Stream = array();
        $this->Channel = $Channel;
        switch ($this->System->OutType) {
            case 'JSON':
                header('Content-type: application/json; charset=utf-8');
            break;
            case 'XML':
                header('Content-type: text/xml; charset=utf-8');
            break;
            case 'HTML':
                header('Content-type: text/html; charset=utf-8');
            break;
            default:
            break;
        }
    }
    
    public function setChannel($Channel){
        $this->Channel = $Channel;
    }
    
    public function Read($Channel=null) {
        if (empty($Channel)){
            $Channel = $this->Channel;
        }
        return($this->Stream[$Channel]);
    }
    
    public function Write($Output, $Channel=null) {
        if (empty($Channel)){
            $Channel = $this->Channel;
        }
        $this->Stream[$Channel][] = $Output;
    }
    
    public function WriteLine($Output, $Channel=null) {
        if (empty($Channel)){
            $Channel = $this->Channel;
        }
        $this->Stream[$Channel][] = $Output;
    }
    
    public function WriteAlone($Output, $Channel=null) {
        if (empty($Channel)){
            $Channel = $this->Channel;
        }
        $this->Stream[$Channel] = $Output;
    }
    
    public function __print($Channel=null) {
        if (empty($Channel)){
            $Channel = $this->Channel;
        }
        switch ($this->System->OutType) {
            case 'JSON':
                $JSON = new JSON();
                print($JSON->Encode($this->Stream[$Channel]));
                //print($this->Stream[$Channel]);
            break;
            case 'XML':
                print('<?xml version="1.0" encoding="UTF-8"?>
                    <Franklin>
                        <Error>
                            XML output type is not implemented yet.
                        </Error>
                    </Franklin>');
            break;
            case 'HTML':
                if (is_array($this->Stream[$Channel])){
                    foreach ($this->Stream[$Channel] as $Out) {
                        print($Out . "<br>");
                    }
                } else{
                    print($this->Stream[$Channel]);
                }
            break;
            default:
                print("This Output Type is not supported yet.");
            break;
        }
    }
    
    public function __toString() {
        return("#Franklin Stream#");
    }
    
}
