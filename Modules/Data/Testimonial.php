<?php

/**
 * Testimonial Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Testimonial extends \Franklin\System\Object{
    
    public $Id;
    public $Name;
    public $Image;
    public $Excerpt; 
    public $Content; 
    public $Signature; 
    public $Language; 
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Language = new \Franklin\Data\Language($this);
    }
    
}
