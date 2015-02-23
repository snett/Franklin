<?php

/**
 * City Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class City extends \Franklin\System\Object{
    
    public $Id;
    public $Name;
    public $Status;
    public $Country;
    public $Territory;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Country = new Country($this);
        $this->Territory = new Territory($this);
    }
    
}
