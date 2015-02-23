<?php

/**
 * Menu Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Component;

class Menu extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $CleanURL;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new Status($this);
    }
    
}
