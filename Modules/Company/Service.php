<?php

/**
 * Service Module
 *
 */

namespace Franklin\Company;

class Service extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Icon;
    public $Title;
    public $Lead;
    public $Article;
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
                } else if($Field['Name'] == 'TitleHU' || $Field['Name'] == 'TitleEN'){
                    $Object->Title = $Result['Title'.$this->System->Language->CodeUC];
                } else if($Field['Name'] == 'LeadHU' || $Field['Name'] == 'LeadEN'){
                    $Object->Lead = $Result['Lead'.$this->System->Language->CodeUC];
                } else if($Field['Name'] == 'ArticleHU' || $Field['Name'] == 'ArticleEN'){
                    $Object->Article = $Result['Article'.$this->System->Language->CodeUC];
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
