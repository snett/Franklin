<?php

/**
 * Box Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Basics;

class Box extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Language;
    public $CleanURL;
    public $Revision;
    public $Title;
    public $Place;
    public $Content;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
