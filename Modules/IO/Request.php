<?php

/**
 * Request Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\IO;

class Request {
    
    public $Type;
    
    public function __construct($Type=null) {
        $this->Type = $this->initType($Type);
    }
    
    private function initType($Type) {
        switch ($Type) {
            case 'get':
            case 'GET':
            case INPUT_GET:
                $Type = INPUT_GET;
            break;
            case 'post':
            case 'POST':
            case INPUT_POST:
                $Type = INPUT_POST;
            break;
            default:
                $Type = INPUT_POST;
            break;
        }
        return($Type);
    }
    
    public function get($Parameter, $Type=null) {
        if (empty($Type)){
            $Type = $this->Type;
        } else{
            $Type = $this->initType($Type);
        }
        return(filter_input($Type, $Parameter));
    }
    
}
