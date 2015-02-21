<?php
/**
 * Country Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Ingredients extends \Franklin\System\Object{
    
    public $Id;
    public $Name;
    public $Image;
    public $Content; 
    public $Language; 
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
