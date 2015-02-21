<?php

/**
 * Comment Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\Forum;

class Comment extends \Franklin\System\Object{

    public $Id;
    public $Name;
    public $Status;
    public $CleanURL;
    public $Revision;
    public $Title;
    public $Content;
    public $User;           //(object) User: Sender of Comment (User/Role:user)
    public $Accepted;       //boolean set by Sender (User/Role:user)

    public $ParentType;     //Question || Comment
    public $Parent;         // Id of Q or C
    
    public $TimeCreated;
    public $TimeModified;
    public $TimeRemoved;
    
    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basics\Status($this);
        $this->Language = new \Franklin\Data\Language($this);
        $this->User = new \Franklin\User\User($this);
        //$this->Topic = new Topic($this);
        $this->UserTouched = new \Franklin\User\User($this);
    }
    
}
