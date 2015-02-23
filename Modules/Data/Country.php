<?php
/**
 * Country Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Data;

class Country extends \Franklin\System\Object{
    
    public $Id;
    public $Name; /*Translateable*/
    public $Status;
    public $Alpha2; /*ISO 3166-1*/
    public $Alpha3; /*ISO 3166-1*/
    public $ccTLD; /*ISO 3166-1*/
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
    }
    
}
