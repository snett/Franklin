<?php

/**
 * Article Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Blog;

class Article extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Title;
    public $Topic;
    public $Status;
    public $Language;
    public $CleanURL;
    public $Revision;
    public $Intro;
    public $Content;
    public $Rate;
    public $Rating;
    public $RatingName;
    
    public $TimeCreated;
    public $TimePublished;
    public $TimeModified;
    public $TimeRemoved;
    public $UserTouched;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
        $this->Topic = new Topic($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
    public function Runtime() {
        if (empty($this->Title)){
            $this->Title = $this->Name;
        }
        if (empty($this->CleanURL)){
            $this->CleanURL = $this->Title;
        }
        $RatingNames[1] = 'one';
        $RatingNames[2] = 'two';
        $RatingNames[3] = 'three';
        $RatingNames[4] = 'four';
        $RatingNames[5] = 'five';
        $this->RatingName = $RatingNames[$this->Rate];
    }
    
}
