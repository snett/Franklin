<?php

/**
 * Object Abstract
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\System;

abstract class Object {
    
    public $System;
    
    public $Name;
    public $ClassName = array();
    public $Parent;
    public $Object = array();
    
    public $Fields = array();
    public $Properties;
    
    public $Logger;
    
    public $Status;
    public $Notes;
    
    public $Translation;
    
    public $Group;
    public $Type;
    
    public $Photos;
    public $Statistics;
    
    public $Database;
    public $StructureFile;
    
    public $UserTouched;

    public $Exceptions = array();
    public $Functions = array();
    
    //abstract function __Fields();
    //abstract function __Exceptions();
    
    public function __construct($Parent) {
        $this->Parent = $Parent;
        $this->System = $this->Parent->System;
        $this->ClassName = explode('\\', get_class($this));
        $this->Name = end($this->ClassName);
        $this->Object['Name'] = $this->Name;
        $this->Object['Table'] = $this->Object['Name'];
        
        $this->StructureFile = $this->System->Directory['Structures'] . $this->ClassName[1] . "/" . $this->Name . ".xml";
        $this->LoadStructure();
        
        /*$this->__Fields();
        $this->__Exceptions();*/
        
        if (!defined('OBJECT_FIELD_NUMERIC')){
            define('OBJECT_FIELD_NUMERIC', 'numeric');
        }
        
        //$this->Translation = new \Franklin\Localization\Translation($this);
    }
    
    
    public function Load($Id=null) {
        if (empty($Id)){
            $Id = $this->Id;
        }
        $Conditions['Id'] = $Id;
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        foreach ($this->Fields as $Field) {
            if ($Field['Type'] == 'object'){
                //error_log($Field['Name'] . " @ " . $this->Object['Table']);
                $this->$Field['Name']->Load($Result[$Field['Name']]);
            } else{
                $this->$Field['Name'] = $Result[$Field['Name']];
            }
        }
        $this->Runtime();
        return($this);
    }
    
    public function Get($Id) {
        $Conditions['Id'] = $Id;
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        $Object = new \stdClass();
        foreach ($this->Fields as $Field) {
            if ($Field['Type'] == 'object'){
                if (!empty($Field['Class'])){
                    $Object->$Field['Name'] = new $Field['Class']($this);
                } else if (!empty ($Field['Object'])){
                    $Object->$Field['Name'] = new $Field['Object']($this);
                } else{
                    $Object->$Field['Name'] = new $Field['Name']($this);
                }
                if (is_object($Object->$Field['Name'])){
                    $Object->$Field['Name']->Load($Result[$Field['Name']]);
                }
            } else{
                $Object->$Field['Name'] = $Result[$Field['Name']];
            }
        }
        return($Object);
    }
    
    public function Ls($Filter="", $Order="", $Limit=100) {
        $ObjectList = array();
        $Results = $this->System->Database->getData($this->Object['Table'], $Filter, $Order, $Limit);
        foreach ($Results as $Result) {
            $ThisClass = get_class($this);
            $Object = new $ThisClass($this);
            foreach ($this->Fields as $Field) {
                if ($Field['Type'] == 'object'){
                    if (is_object($Object->$Field['Name'])){
                        $Object->$Field['Name']->Load($Result[$Field['Name']]);
                    }
                } else{
                    $Object->$Field['Name'] = $Result[$Field['Name']];
                }
            }
            $Object->Runtime();
            $ObjectList[] = $Object;
        }
        return($ObjectList);
    }
    
    public function Create($Parameters=array()) {
        if (empty($Parameters)){
            foreach ($this->Fields as $Field) {
                if ($Field['Type'] == 'object'){
                    if (is_object($this->$Field['Name'])){
                        $Parameters[$Field['Name']] = $this->$Field['Name']->Id;
                    }
                } else{
                    $Parameters[$Field['Name']] = $this->$Field['Name'];
                }
            }
        }
        return($this->System->Database->InsertArray($this->Object['Table'], $Parameters));
    }
    
    public function Set($Id="", $Parameters=array()) {
        if (empty($Id)){
            $Id = $this->Id;
        }
        $Conditions['Id'] = $Id;
        if (empty($Parameters)){
            foreach ($this->Fields as $Field) {
                if ($Field['Type'] == 'object'){
                    if (is_object($this->$Field['Name'])){
                        $Parameters[$Field['Name']] = $this->$Field['Name']->Id;
                    }
                } else{
                    $Parameters[$Field['Name']] = $this->$Field['Name'];
                }
            }
        }
        return($this->System->Database->UpdateArray($this->Object['Table'], $Parameters, $Conditions));
    }
    
    public function Runtime() {
        return(false);
    }
    
    public function Save(){
        if (!$this->thisExists()){
            $this->Create();
        } else{
            $this->Set();
        }
    }
    
    public function thisExists() {
        $Conditions['Id'] = $this->Id;
        return($this->System->Database->Exists($this->Object['Table'], $Conditions));
    }
    
    public function idExists($Id) {
        $Conditions['Id'] = $Id;
        return($this->System->Database->Exists($this->Object['Table'], $Conditions));
    }
    
    public function Exists($Conditions) {
        return($this->System->Database->Exists($this->Object['Table'], $Conditions));
    }
    
    public function Find($Conditions) {
        $Result = $this->System->Database->getRow($this->Object['Table'], $Conditions);
        $ThisClass = get_class($this);
        $Object = new $ThisClass($this);
        $Object->Load($Result['Id']);
        return($Object);
    }
    
    public function lastId() {
        $Result = $this->System->Database->getData($this->Object['Table'], "", "`Id` DESC", 1, "", "", $rows="Id");
        return($Result[0]['Id']);
    }
    
    public function nextId() {
        return($this->lastId() + 1);
    }
    
    //TODO hasPermission(UserGroup, action, this){}
    /*public function hasPermission($Action) {
        $Permission = new \Franklin\Security\Permission($this);
        $Conditions["Group"] = $this->System->User->Group->Id;
        $Conditions["ElementType"] = $this->Parent->Name;
        //$Conditions["ElementId"] = $this->Id;
        $Conditions["action"] = $Action; //Read, Write, Remove
        $Permission->Find($Conditions);
    }*/
    
    //TODO __call magic => check permissions: method() => __call => hasPermission => fwd:method()
    
    public function Remove($Conditions=array()) {
        if (empty($Conditions)){
            $Conditions["Id"] = $this->Id;
        }
        return($this->System->Database->removeRow($this->Object['Table'], $Conditions));
    }
    
    public function Build(){
        if (!$this->System->Database->TableExists($this->Object['Table'])){
            if ($this->System->Database->CreateTable($this->Object['Table'], $this->Fields)){
                return(true);
            } else{
                throw new \Exception("Unable to build the object ($this->Name).", 2);
            }
            //foreach ($this->Fields as $FieldKey => $Field) {}
        } else{
            throw new \Exception("$this->Name object is already built. Try to rebuild.", 1);
        }
    }
    
    public function ReBuild(){
        if ($this->System->Database->EditTable($this->Fields)){
            return(true);
        } else{
            throw new Exception("Unable to rebuild the object ($this->Name).", 3);
        }
        //foreach ($this->Fields as $FieldKey => $Field) {}
    }
    
    public function Destroy(){
        return($this->System->Database->RemoveTable($this->Object['Table']));
    }
    
    public function AddField($Field) {
        $this->Fields[] = $Field;
    }
    
    public function AddFields($Fields){
        foreach ($Fields as $Field) {
            $this->AddField($Field);
        }
    }
    
    public function LoadStructure($StructureFile="") {
        if (!$StructureFile){
            $StructureFile = $this->StructureFile;
        }
        $Structure = new \Franklin\IO\XML($StructureFile);
        $Fields = $Structure->XML->Fields;
        foreach ($Fields->Field as $Field){
            $F = array();
            $F['Name'] = (string) $Field->Name;
            $F['Type'] = (string) $Field->Type;
            $F['Object'] = (string) $Field->Object;
            $F['Class'] = (string) $Field->Class;
            $F['Length'] = (string) $Field->Length;
            $F['Index'] = (string) $Field->Index;
            $F['Required'] = (string) $Field->Required;
            $F['Pattern'] = (string) $Field->Required;
            $F['Relation'] = (string) $Field->Relation;
            $F['NotNull'] = (string) $Field->NotNull;
            $F['Extras'] = array();
            if (!empty($Field->Extras->Extra)){
                foreach ($Field->Extras->Extra as $Extra) {
                    $F['Extras'][] = (string) $Extra;
                }
            }
            $this->AddField($F);
        }
    }
    
    public function AddException($Exception) {
        $this->Exceptions[] = $Exception;
    }
    
    public function AddExceptions($Exceptions){
        //TODO write correctly
        foreach ($Exceptions as $Exception) {
            $this->AddException($Exception);
        }
    }
    
    public function AddFunction($Function) {
        $this->Functions[] = $Function;
    }
    
    public function AddFunctions($Functions){
        foreach ($Functions as $Function) {
            $this->AddFunction($Function);
        }
    }
    
    public function ClearFunctions() {
        $this->Functions = array();
    }
    
    public function loadFunctions() {
        /*foreach(get_class_methods($this) as $Method){
            $Function['Name'] = $Method;
            $this->AddFunction($Function);
        }*/
        $Function = array();
        $Function['Name'] = 'Get';
        $Function['Description'] = 'Get ' . $this->Name . ' by its Id.';
        $Function['Arguments'] = array();
        $Function['Arguments'][] = array('Id' , 'Id (int) || null - Id of required ' . $this->Name . '');
        $Function['Return'] = 'Object';
        $this->AddFunction($Function);
        $Function = array();
        $Function['Name'] = 'Set';
        $Function['Description'] = 'Set ' . $this->Name . ' fields to its object values or to values of a specific parameter array.';
        $Function['Arguments'] = array();
        $Function['Arguments'][] = array('Id', 'Id (int) || null - Id of ' . $this->Name . ' to set');
        $Function['Arguments'][] = array('Parameters', 'Parameters (array) || null - Array of parameters to set: Parameters[Field] => Value');
        $Function['Return'] = 'Database Result';
        $this->AddFunction($Function);
        $Function = array();
        $Function['Name'] = 'Create';
        $Function['Description'] = 'Create ' . $this->Name . ' fields by its object values or by values of a specific parameter array.';
        $Function['Arguments'] = array();
        $Function['Arguments'][] = array('Parameters', 'Parameters (array) || null - Array of parameters to create: Parameters[Field] => Value');
        $Function['Return'] = 'Database Result';
        $this->AddFunction($Function);
        $Function = array();
        $Function['Name'] = 'Ls';
        $Function['Description'] = 'List ' . $this->Name . ' Objects by Filter, Order and Limit.';
        $Function['Arguments'] = array();
        $Function['Arguments'][] = array('Filter', 'Filter (array) || null - Array of filters: Filter[Field] => Value');
        $Function['Arguments'][] = array('Order', 'Order (string) || null - Simple "OrderBy" string. Eg. "`Name` DESC"');
        $Function['Arguments'][] = array('Limit', 'Limit (int) || null - Maximum number of returning Objects');
        $Function['Return'] = 'Array of Objects';
        $this->AddFunction($Function);
        $Function = array();
        $Function['Name'] = 'Find';
        $Function['Description'] = 'Find ' . $this->Name . ' by its Field Name and Field Value pair.';
        $Function['Arguments'] = array();
        $Function['Arguments'][] = array('Filter', 'Filter (array) || null - Array of filters: Filter[Field] => Value');
        $Function['Return'] = 'Object';
        $this->AddFunction($Function);
        $Function = array();
        $Function['Name'] = 'Save';
        $Function['Description'] = 'Create ' . $this->Name . ' if it\'s not already exist or Set ' . $this->Name . ' if it does exist.';
        $Function['Arguments'] = array();
        $Function['Return'] = 'Object';
        $this->AddFunction($Function);
        $Function = array();
    }
    
}
