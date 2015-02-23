<?php

/**
 * Language Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Language extends \Franklin\System\Object{
    
    public $Id;
    public $Name;
    public $Status;
    public $Family;
    public $NativeName;
    
    public $Code;       /*ISO 639-1*/
    public $CodeUC;     /*ISO 639-1 Upper Case*/
    public $UnixCode;   /*Unix Code*/
    public $Code2;      /*ISO 639-2*/
    public $Code2T;     /*ISO 639-2/T*/
    public $Code2B;     /*ISO 639-2/B*/
    public $Code3;      /*ISO 639-3*/
    public $Code6;      /*ISO 639-6*/
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
    public function Load($Id = null) {
        parent::Load($Id);
        $this->CodeUC = strtoupper($this->Code);
    }
    
    public function LoadByCode($Code) {
        $Conditions['Code'] = $Code;
        $Object = $this->Find($Conditions);
        $this->Load($Object->Id);
        $this->CodeUC = strtoupper($this->Code);
    }
    
}
