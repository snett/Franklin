<?php

/**
 * JSON Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\IO;

class JSON {
    
    public $JSON;
    
    public function Encode($String) {
        return($this->EncodeString($String));
    }
    
    public function Decode($String) {
        return($this->DecodeString($String));
    }
    
    public function EncodeString($String) {
        return(json_encode($String));
    }
    
    public function DecodeString($JSON) {
        return(json_decode($JSON));
    }
    
}
