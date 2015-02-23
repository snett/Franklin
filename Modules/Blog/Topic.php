<?php

/**
 * Topic Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Blog;

class Topic extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Language;
    public $CleanURL;
    public $Title;
    public $Image;
    
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;

    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
    public function Runtime() {
        if (empty($this->Title)){
            $this->Title = $this->Name;
        }
        if (empty($this->CleanURL)){
            $this->CleanURL = $this->Title;
        }
    }
    
}
