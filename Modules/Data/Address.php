<?php

/**
 * Address Module
 *
 * @author Bálint Horváth  <balint@snett.net>
 */

namespace Franklin\Data;

class Address extends \Franklin\System\Object{
    
    public $Id;
    public $Name;
    public $Status;
    public $Country;
    public $Territory;
    public $Zip;
    public $City;
    public $Street;
    public $Street2;
    public $Street3;
    public $Ext;
    public $Note;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basics\Status($this);
        $this->Country = new \Franklin\Data\Country($this);
        $this->Territory = new \Franklin\Data\Territory($this);
        $this->City = new \Franklin\Data\City($this);
    }
    
}