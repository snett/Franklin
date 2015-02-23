<?php

/**
 * Question Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Forum;

class Question extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $Language;
    public $CleanURL;
    public $Revision;
    public $Featured;
    public $Title;
    public $Content;
    public $Answered;   //Runtime boolean value, true if there's a (/an Accepted) Comment on it by the seleced Specialist
    public $User;       //User role: user
    public $Specialist; //User Role: specialist
    public $Topic;      //(object) Topic of question
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basic\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
        $this->User = new \Franklin\User\User($this);
        $this->Specialist = new \Franklin\User\User($this);
        $this->Topic = new Topic($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
    public function Runtime() {
        $CommentObject = new Comment($this);
        $Filter = "`ParentType`='Question' AND `Parent`='$this->Id' AND `Accepted`='1'";
        $Comments = $CommentObject->Ls($Filter);
        if (count($Comments) > 0){
            $this->Answered = true;
        } else{
            $this->Answered = false;
        }
    }
    
}
