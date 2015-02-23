<?php

/**
 * Page Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Component;

class Page extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Language;
    public $CleanURL;
    public $Revision;
    public $Intro;
    public $Content;
    
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;
    public $UserTouched;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new Status($this);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
