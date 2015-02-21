<?php

/**
 * User Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\User;

class User extends \Franklin\System\Object{

    public $Id;
    public $Status;
    public $Role;               //user | specialist | subscriber | admin | support | marketing
    public $Name;
    public $Password;
    public $UserName;
    public $Avatar;
    public $Speciality;
    public $Description;
    public $Title;
    public $FirstName;
    public $LastName;
    public $FullName;
    public $SuffixedName;
    public $Email;
    public $PL;                 //Permission Level
    public $RL;                 //Responsible Language
    public $RLA;                //Responsible Language Array
    public $Newsletter;
    public $NewsletterCategory;
    public $TimeCreated;
    public $TimeModified;
    public $TimeLastLogin;
    public $TimeRemoved;
    public $Topics = array();   //Array of (object) Topic Ids [runtime generated]

    private $Sec = 562;
    private $UsraSession;

    public function __construct($Parent) {
        parent::__construct($Parent);
        $this->Status = new \Franklin\Basics\Status($this);
        $this->UsraSession = md5('Usra' . $this->Sec);
    }
    
    public function Login($Username, $Password) {
        $Conditions['Email'] = $Username;
        $Conditions['Password'] = self::Encode($Password);
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        if ($Result !== false){
            $this->Load($Result['Id']);
            $this->System->Session->Set($this->UsraSession, $Result['Id'] * $this->Sec);
        } else{
            return(false);
        }
    }
    
    public function Runtime() {
        if ($this->RL == "{all}"){
            $LanguageObject = new \Franklin\Data\Language($this);
            foreach($LanguageObject->Ls("`Status`='1'") as $Language){
                $this->RLA[] = (int) $Language->Id;
            }
        } else{
            $this->RLA = explode("|", $this->RL);
        }
        if (!empty($this->Title)){
            $this->FullName = $this->Title . " ";
        }
        if (in_array($this->System->Language->Code, array('hu'))){
            $this->FullName .= $this->FirstName . " " . $this->LastName;
        } else{
            $this->FullName .= $this->LastName . " " . $this->FirstName;
        }
    }
    
    public function isRegistered($Email=""){
        if (empty($Email)){
            $Email = $this->Email;
        }
        if ($this->Exists(array('Email'=>$Email))){
            return(true);
        } else{
            return(false);
        }
    }
    
    public function Logout() {
        $this->System->Session->Set($this->UsraSession, null);
        $this->System->Session->Close();
    }
    
    public static function Encode($Password) {
        return(md5($Password[3] . "0SN46P" . md5($Password[2] . 'Fr4nkl1n' . $Password[4]) . $Password[0] . "#" . $Password));
    }
    
    public function LoggedIn() {
        $Usra = $this->System->Session->Get($this->UsraSession);
        if (!empty($Usra)){
            return(true);
        } else{
            return(false);
        }
    }
    
    public function selfLoad(){
        $this->Load($this->System->Session->Get($this->UsraSession) / $this->Sec);
    }
    
    public function Load($Id="") {
        if (empty($Id)){
            $Id = $this->Id;
        }
        $Conditions['Id'] = $Id;
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        $this->Id = $Result['Id'];
        $this->UserName = $Result['UserName'];
        $this->FirstName = $Result['FirstName'];
        $this->LastName = $Result['LastName'];
        $this->Name = $Result['Name'];
        $this->Email = $Result['Email'];
        $this->Role = $Result['Role'];
        $this->RL = $Result['RL'];
        $this->PL = $Result['PL'];
        $this->Avatar = $Result['Avatar'];
        $this->Title = $Result['Title'];
        $this->Speciality = $Result['Speciality'];
        $this->Description = $Result['Description'];
        $this->Newsletter = $Result['Newsletter'];
        $this->NewsletterCategory = $Result['NewsletterCategory'];
        $this->Runtime();
        return($this);
    }
    
}
