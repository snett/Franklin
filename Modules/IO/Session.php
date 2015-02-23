<?php

/**
 * Session Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\IO;

class Session {
    
    public $System;
    
    private $Name;

    public function __construct($System, $Name="") {
        $this->System = $System;
        if (empty($Name)){
            $this->Name = "Franklin." . rand(10001, 28000) . "." . date("His");
        } else{
            $this->Name = $Name;
        }
        $this->Start();
    }
    
    public function Set($Name, $Value) {
        $_SESSION[$Name] = $Value;
    }
    
    public function Get($Name) {
        return($_SESSION[$Name]);
    }
    
    public function getName() {
        return($this->Name);
    }

    public function Start() {
        //session_name($this->Name);
        session_start();
    }
    
    public function Close() {
        session_write_close();
        session_destroy();
    }
    
}
