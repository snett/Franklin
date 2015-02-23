<?php

/**
 * Service Module
 * @author Zsolt Erdélyi <zsolt@ideapp.net>
 * @author ideApp <dev@ideapp.net>
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Company;

class Service extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Icon;
    public $Title;
    public $Lead;
    public $Article;
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
