<?php

/**
 * Meta Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Meta extends \Franklin\System\Object{
    
    public $Id;
    public $CleanURL;
    public $Revision;
    public $Status;
    public $Language;
    public $Title;
    public $Description;
    public $Keywords;
    public $Index;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
}
