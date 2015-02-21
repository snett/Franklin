<?php
/**
 * Group Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Basics;

class Group extends \Franklin\System\Object {
    
    public $Id;
    public $Name;
    public $Status;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new Status($this);
    }
    
}
