<?php

/**
 * URL Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\System;

class URL {
    
    private $System;

    public $Mode;
    
    public $URL;
    public $plainURL;
    
    public $Query;
    
    public $Q;
    public $QPer;
    
    public $CurrentURL;
    public $Application;
    public $Action;
    public $Routine;
    public $SubRoutine;
    
    public $Scheme;
    public $User;
    public $Password;
    public $Host;
    public $Port;
    public $Path;
    public $Fragment;
    
    public $REQUEST_URI;

    private $Server;
    
    public function __construct($System, $URL="", $Mode="REQUEST", $Query="") {
        $this->System = $System;
        if (!defined('URL_MODE_REQUEST')){
            define('URL_MODE_REQUEST', 'REQUEST');
        }
        if (!defined('URL_MODE_ALIAS')){
            define('URL_MODE_ALIAS', 'ALIAS');
        }
        $this->Mode = $Mode;
        if (empty($URL)){
            $this->Auto();
        } else{
            if (is_object($URL)){
                $this->URL = $URL;
                $this->buildURL();
            } else{
                $this->plainURL = $URL;
                $this->parseURL($URL);
            }
        }
        if (!empty($Query)){
            $this->Query = $Query;
        } else{
            $this->Query = $this->Path;
        }
    }
    
    public function parseURL($plainURL) {
        $ParsedURL = parse_url($plainURL);
        $this->URL = $ParsedURL;
        if ($this->URL !== false){
            $this->Scheme = $this->URL['scheme'];
            $this->User = $this->URL['user'];
            $this->Password = $this->URL['pass'];
            $this->Host = $this->URL['host'];
            $this->Port = $this->URL['port'];
            $this->Path = $this->URL['path'];
            $this->Query = $this->URL['query'];
            $this->Fragment = $this->URL['fragment'];
        }
        $this->HTTP_HOST = $this->Host;
        $this->REQUEST_URI = $this->Path . "?" . $this->Query . '#' . $this->Fragment;
        $this->buildQ();
    }
    
    private function buildQ() {
        if ($this->Mode == URL_MODE_ALIAS){
            $this->Q = str_replace('/q=', '', $this->Path);
        } else {
            $this->Q = filter_input(INPUT_GET, 'q');
        }
        $this->QPer = explode('/', $this->Q);
    }
    
    public function setParams() {
        $this->Application = $this->QPer[0];
        $this->Action = $this->QPer[1];
        $this->Routine = $this->QPer[2];
        $this->SubRoutine = $this->QPer[3];
        $this->RoutineParam = $this->QPer[4];
        
        $CurrentURL = array();
        if (!empty($this->Application)){ $CurrentURL[] = $this->Application; }
        if (!empty($this->Action)){ $CurrentURL[] = $this->Action; }
        if (!empty($this->Routine)){ $CurrentURL[] = $this->Routine; }
        if (!empty($this->SubRoutine)){ $CurrentURL[] = $this->SubRoutine; }
        if (!empty($this->RoutineParam)){ $CurrentURL[] = $this->RoutineParam; }
        $this->CurrentURL = implode('/', $CurrentURL);
        
        $this->System->Application = $this->Application;
        $this->System->Action = $this->Action;
        $this->System->Routine = $this->Routine;
        $this->System->SubRoutine = $this->SubRoutine;
        $this->System->RoutineParam = $this->RoutineParam;
    }
    
    public function buildURL() {
        $this->plainURL = $this->Scheme . '://' . $this->Host . $this->REQUEST_URI;
    }
    
    public function __get($Part){
        return($this->URL[$Part]);
    }
    
    public function __toString() {
        return($this->plainURL);
    }
    
    private function Auto(){
        $this->Server = filter_input_array(INPUT_SERVER);
        $this->Host = $this->Server['HTTP_HOST'];
        $this->REQUEST_URI = $this->Server['REQUEST_URI'];
        $this->Scheme = (isset($this->Server['HTTPS']) && $this->Server['HTTPS'] !== 'off') ? 'https' : 'http';
        $this->buildURL();
        $this->parseURL($this->plainURL);
    }
    
}
