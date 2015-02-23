<?php

/**
 * Alias Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Alias extends \Franklin\System\Object{
    
    public $Id;
    public $Status;
    public $Language;
    public $From;
    public $To;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
