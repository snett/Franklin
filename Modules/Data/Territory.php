<?php

/**
 * Territory Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Territory extends \Franklin\System\Object{
    
    public $Id;
    public $Name;
    public $Status;
    public $Country;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Country = new Country($this);
    }
    
}
