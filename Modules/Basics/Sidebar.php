<?php

/**
 * Sidebar Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Basics;

class Sidebar extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Language;
    public $CleanURL;
    public $Revision;
    public $Place;
    public $Title;
    public $Content;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new Status($this);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
