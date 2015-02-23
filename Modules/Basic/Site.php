<?php

/**
 * Site Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Basic;

class Site extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Domain;
    public $Status;     //Category status {Status Object}
    public $Language;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new Status($this);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
