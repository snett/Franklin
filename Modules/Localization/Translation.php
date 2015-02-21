<?php

/**
 * Translation Module
 *
 * @author Bálint Horváth
 */

namespace Franklin\Localization;

class Translation extends \Franklin\System\Object{
    
    public $Id;
    public $Status;
    public $ElementType;
    public $ElementField;
    public $ElementId;
    public $Language;
    public $Value;
    public $Note;
    
    //public $Content;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->ElementType = $Parent->ClassName[1] . "\\" . $Parent->Name;
        $this->ElementId = $Parent->Id;
        $this->Status = new \Franklin\Basics\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
    /*public function Create($Parameters = array()) {
        if (empty($Parameters)){
            foreach ($this->Parent->Fields as $PField){
                foreach ($this->Parent->Translation->$PField['Name'] as $PFLang => $TPField){
                    if (!is_object($this->Parent->$PField['Name']) && !empty($this->Parent->Translation->$PField['Name']->$PFLang)){
                        $this->Id = null;
                        $this->ElementType = $this->Parent->FullName;
                        $this->ElementField = $PField['Name'];
                        $this->ElementId = $this->Parent->$PField['Name']->Id;
                        $this->Language->LoadByCode($PFLang);
                        $this->Value = $TPField;
                        
                        parent::Create();
                    }
                }
            }
        } else{
            parent::Create($Parameters);
        }
    }*/
    
    public function Load($Id="") {
        if (!empty($Id)){
            parent::Load($Id);
        } else{
            foreach ($this->Parent->Fields as $PField){
                $this->$PField['Name'] = new \stdClass();
                $LC = $this->System->Language->CodeUC;
                $LId = $this->System->Language->Id;
                $PClass = $this->Parent->ClassName[1] . "\\\\" . $this->Parent->ClassName[2];
                $FName = $PField['Name'];
                $PId = $this->Parent->Id;
                $Conditions['Status'] = '1';
                $Conditions['ElementType'] = $PClass;
                $Conditions['ElementField'] = $FName;
                $Conditions['ElementId'] = $PId;
                $Conditions['Language'] = $LId;
                $Translated = $this->getByConditions($Conditions);
                if (empty($Translated->Value)){
                    $this->$PField['Name']->$LC = $this->Parent->$PField['Name'];
                } else{
                    $this->$PField['Name']->$LC = $Translated->Value;
                }
            }
        }
        //$this->Content = "Test";
    }
    
    public function getByConditions($Conditions) {
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        $Object = new \stdClass();
        //$ThisClass = get_class($this);
        //$Object = new $ThisClass($this);
        foreach ($this->Fields as $Field) {
            if ($Field['Type'] != 'object'){
                $Object->$Field['Name'] = $Result[$Field['Name']];
            }
        }
        return($Object);
    }
    
}