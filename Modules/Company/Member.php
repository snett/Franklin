<?php

/**
 * Member Module
 *
 */

namespace Franklin\Company;

class Member extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $FullName;
    public $Status;
    public $Title;
    public $Bio;
    public $ImageURL;
    public $Email;
    public $Facebook;
    public $Twitter;
    public $GooglePlus;
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;
    public $UserTouched;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basics\Status($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
    public function Ls($Filter="", $Order="", $Limit=10) {
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
                } else if($Field['Name'] == 'BioHU' || $Field['Name'] == 'BioEN'){
                    $Object->Bio = $Result['Bio'.$this->System->Language->CodeUC];
                } else{
                    $Object->$Field['Name'] = $Result[$Field['Name']];
                }
            }
            $Object->Runtime();
            $ObjectList[] = $Object;
        }
        return($ObjectList);
    }
}
