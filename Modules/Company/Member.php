<?php

/**
 * Member Module
 * @author Zsolt Erdélyi <zsolt@ideapp.net>
 * @author ideApp <dev@ideapp.net>
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Company;

class Member extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $FullName;
    public $Status;
    public $Title;
    public $Bio;
    public $ImageURL;
    public $Email;
    public $Facebook;
    public $Twitter;
    public $GooglePlus;
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;
    public $UserTouched;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
}
