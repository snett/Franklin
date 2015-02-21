<?php

/**
 * Term Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Localization;

class Term extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Content;
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basics\Status($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
    public function Ls($Filter="", $Order="", $Limit=100) {
        $ObjectList = array();
        $Results = $this->System->Database->getData($this->Object['Table'], $Filter, $Order, $Limit);
        foreach ($Results as $Result) {
            //$Object = new \stdClass();
            $ThisClass = get_class($this);
            $Object = new $ThisClass($this);
            foreach ($this->Fields as $Field) {
                if ($Field['Type'] == 'object'){
                    if (is_object($Object->$Field['Name'])){
                        $Object->$Field['Name']->Load($Result[$Field['Name']]);
                    }
                } else{
                    $Object->$Field['Name'] = $Result[$Field['Name']];
                    //$Object->Translation = new Translation($Object);
                    //$Object->Translation->Load();
                }
            }
            $Object->Runtime();
            $ObjectList[] = $Object;
        }
        return($ObjectList);
    }
    
    public function getByName($Name) {
        $Conditions['Name'] = $Name;
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        //$Object = new \stdClass();
        $ThisClass = get_class($this);
        $Object = new $ThisClass($this);
        foreach ($this->Fields as $Field) {
            if ($Field['Type'] == 'object'){
                if (!empty($Field['Class'])){
                    $Object->$Field['Name'] = new $Field['Class']($this);
                } else if (!empty ($Field['Object'])){
                    $Object->$Field['Name'] = new $Field['Object']($this);
                } else{
                    $Object->$Field['Name'] = new $Field['Name']($this);
                }
                if (is_object($Object->$Field['Name'])){
                    $Object->$Field['Name']->Load($Result[$Field['Name']]);
                }
            } else{
                $Object->$Field['Name'] = $Result[$Field['Name']];
                //$Object->Translation = new Translation($Object);
                //$Object->Translation->Load();
            }
        }
        return($Object);
    }
    
}
