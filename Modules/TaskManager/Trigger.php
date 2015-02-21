<?php

/**
 * Trigger Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\TaskManager;

class Trigger {
    
    public $Parent;
    public $System;
    public $Log;
    
    public $DemoField;
    public $DemoFieldA;
    public $DemoFieldB;
    public $DemoFieldC;
    public $DemoFieldD;

    public function __construct($Parent) {
        $this->Parent = $Parent;
        $this->System = $this->Parent->System;
        $this->DemoField = "Hello";
        $this->DemoFieldA = "Hello";
        $this->DemoFieldB = "1";
        $this->DemoFieldC = "4";
        $this->DemoFieldD = "de";
    }
    
    public function ReadRun() {
        $Trigger = $this->Read();
        return($this->Run($Trigger->Conditions, $Trigger->Reactions));
    }
    
    public function Read() {
        $Trigger->Conditions->OR[0]->Contition->Object = "Trigger";
        $Trigger->Conditions->OR[0]->Contition->Field = "DemoFieldA";
        $Trigger->Conditions->OR[0]->Contition->Value = "=Hello";
        $Trigger->Conditions->OR[1]->AND[0]->Condition->Object = "Trigger";
        $Trigger->Conditions->OR[1]->AND[0]->Condition->Field = "DemoFieldB";
        $Trigger->Conditions->OR[1]->AND[0]->Condition->Value = "=1";
        $Trigger->Conditions->OR[1]->AND[1]->Condition->Object = "Trigger";
        $Trigger->Conditions->OR[1]->AND[1]->Condition->Field = "DemoFieldC";
        $Trigger->Conditions->OR[1]->AND[1]->Condition->Value = ">=2";
        $Trigger->Conditions->OR[1]->AND[2]->Condition->Object = "Trigger";
        $Trigger->Conditions->OR[1]->AND[2]->Condition->Field = "DemoFieldD";
        $Trigger->Conditions->OR[1]->AND[2]->Condition->Value = "=de";
        $Trigger->Reactions[0] = new \stdClass();
        $Trigger->Reactions[0]->Object = "CMC.Email";
        $Trigger->Reactions[0]->Method = "To";
        $Trigger->Reactions[0]->Param[0] = "balint@snett.net";
        $Trigger->Reactions[1] = new \stdClass();
        $Trigger->Reactions[1]->Object = "CMC.Email";
        $Trigger->Reactions[1]->Method = "Subject";
        $Trigger->Reactions[1]->Param[0] = "Test Trigger";
        $Trigger->Reactions[2] = new \stdClass();
        $Trigger->Reactions[2]->Object = "CMC.Email";
        $Trigger->Reactions[2]->Method = "PlainBody";
        $Trigger->Reactions[2]->Param[0] = "Test trigger activated.";
        $Trigger->Reactions[3] = new \stdClass();
        $Trigger->Reactions[3]->Object = "CMC.Email";
        $Trigger->Reactions[3]->Method = "Send";
    }
    
    public function ObsoleteRead() {
        $Trigger->Conditions->OR[0]->Contition->Object = "Store.Order";
        $Trigger->Conditions->OR[0]->Contition->Field = "CouponCode";
        $Trigger->Conditions->OR[0]->Contition->Value = "Minus5";
        $Trigger->Conditions->OR[1]->AND[0]->Condition->Object = "Store.OrderItem";
        $Trigger->Conditions->OR[1]->AND[0]->Condition->Field = "Product.Id";
        $Trigger->Conditions->OR[1]->AND[0]->Condition->Value = "1";
        $Trigger->Conditions->OR[1]->AND[1]->Condition->Object = "Store.OrderItem";
        $Trigger->Conditions->OR[1]->AND[1]->Condition->Field = "Quantity";
        $Trigger->Conditions->OR[1]->AND[1]->Condition->Value = ">=2";
        $Trigger->Conditions->OR[1]->AND[2]->Condition->Object = "Basics.Site";
        $Trigger->Conditions->OR[1]->AND[2]->Condition->Field = "Language.Code";
        $Trigger->Conditions->OR[1]->AND[2]->Condition->Value = "de";
        $Trigger->Reactions->Reaction[0]->Object = "Store.OrderItem";
        $Trigger->Reactions->Reaction[0]->Field = "Price.Value";
        $Trigger->Reactions->Reaction[0]->Value = "-5%";
    }
    
    public function trueCondition($Condition) {
        $Object = explode(".", $Condition->Object);
        $Field = $Condition->Field;
        switch (count($Object)) {
            case 2:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Field};
            break;
            case 3:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Object[2]}->{$Field};
            break;
            case 4:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Object[2]}->{$Object[3]}->{$Field};
            break;
            case 5:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Object[2]}->{$Object[3]}->{$Object[4]}->{$Field};
            break;
            default:
                $Element = $this->System->{$Object[0]}->{$Field};
            break;
        }
        $Value = $Condition->Value;
        if (stripos($Value, '<=') === 0){
            $Operation = '<=';
            $Value = substr($Value, 2);
        } else if (stripos($Value, '>=') === 0){
            $Operation = '>=';
            $Value = substr($Value, 2);
        } else if (stripos($Value, '>') === 0){
            $Operation = '>';
            $Value = substr($Value, 1);
        } else if (stripos($Value, '<') === 0){
            $Operation = '<';
            $Value = substr($Value, 1);
        } else if (stripos($Value, '=') === 0){
            $Operation = '=';
            $Value = substr($Value, 1);
        } else if (stripos($Value, '==') === 0){
            $Operation = '==';
            $Value = substr($Value, 2);
        }
        //return(array($Element, $Operation, $Value));
        $Result = false;
        switch ($Operation) {
            case '==':
                if ($Element === $Value){
                    $Result = true;
                }
            break;
            case '=':
                if ($Element == $Value){
                    $Result = true;
                }
            break;
            case '>':
                if ($Element > $Value){
                    $Result = true;
                }
            break;
            case '<':
                if ($Element < $Value){
                    $Result = true;
                }
            break;
            case '<=':
                if ($Element <= $Value){
                    $Result = true;
                }
            break;
            case '>=':
                if ($Element >= $Value){
                    $Result = true;
                }
            break;
            default:
                $Result = false;
            break;
        }
        return($Result);
    }
    
    public function ORConditions($Conditions) {
        foreach ($Conditions as $Condition) {
            if ($this->trueCondition($Condition)){
                return(true);
            }
        }
        return(false);
    }
    
    public function ANDConditions($Conditions) {
        foreach ($Conditions as $Condition) {
            if (!$this->trueCondition($Condition)){
                return(false);
            }
        }
        return(true);
    }
    
    public function trueConditions($Conditions) {
        foreach ($Conditions as $Type => $Condition) {
            if (!is_object($Condition) && is_array($Condition)){
                return($this->trueConditions($Condition));
            } else if (strtolower($Type) == 'or'){
                return($this->ORConditions($Condition));
            } else if (strtolower($Type) == 'and'){
                return($this->ANDConditions($Condition));
            } else if (strtolower($Type) == 'conditions'){
                return($this->trueConditions($Condition));
            } else{
                return($this->trueCondition($Condition));
            }
        }
    }
    
    public function trueConditionsTest() {
        $Conditions = new \stdClass();
        
        /*$Condition = new \stdClass();
        $Condition->Object = "Trigger";
        $Condition->Field = "DemoField";
        $Condition->Value = "=Hello";
        $Conditions->Condition = $Condition;*/
        
        $Conditions->OR[0]->Condition = new \stdClass();
        $Conditions->OR[0]->Condition->Object = "Trigger";
        $Conditions->OR[0]->Condition->Field = "DemoField";
        $Conditions->OR[0]->Condition->Value = "=Hello";
        
        return($this->trueConditions($Conditions));
    }
    
    public function ExecuteReaction($Reaction) {
        $Object = explode(".", $Reaction->Object);
        $Method = $Reaction->Method;
        $Parameters = (array) $Reaction->Param;
        switch (count($Object)) {
            case 2:
                $Element = $this->System->{$Object[0]}->{$Object[1]};
            break;
            case 3:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Object[2]};
            break;
            case 4:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Object[2]}->{$Object[3]};
            break;
            case 5:
                $Element = $this->System->{$Object[0]}->{$Object[1]}->{$Object[2]}->{$Object[3]}->{$Object[4]};
            break;
            default:
                $Element = $this->System->{$Object[0]};
            break;
        }
        $LogEntry['Object'] = $Object;
        $LogEntry['Method'] = $Method;
        $LogEntry['Parameters'] = $Parameters;
        call_user_func_array(array($Element, $Method), $Parameters);
        $this->Log[] = $LogEntry;
    }
    
    public function ExecuteReactions($Reactions) {
        foreach ($Reactions as $Reaction) {
            $this->ExecuteReaction($Reaction);
        }
    }
    
    public function Run($Conditions, $Reactions) {
        if ($this->trueConditions($Conditions)){
            $this->ExecuteReactions($Reactions);
            return(true);
        } else{
            return(false);
        }
    }
    
    public function TestRun() {
        $Condition = new \stdClass();
        $Condition->Object = "Trigger";
        $Condition->Field = "DemoField";
        $Condition->Value = "=Hello";
        $Reactions[0] = new \stdClass();
        $Reactions[0]->Object = "CMC.Email";
        $Reactions[0]->Method = "To";
        $Reactions[0]->Param[0] = "balint@snett.net";
        $Reactions[1] = new \stdClass();
        $Reactions[1]->Object = "CMC.Email";
        $Reactions[1]->Method = "Subject";
        $Reactions[1]->Param[0] = "Test Trigger";
        $Reactions[2] = new \stdClass();
        $Reactions[2]->Object = "CMC.Email";
        $Reactions[2]->Method = "PlainBody";
        $Reactions[2]->Param[0] = "Test trigger activated.";
        $Reactions[3] = new \stdClass();
        $Reactions[3]->Object = "CMC.Email";
        $Reactions[3]->Method = "Send";
        if($this->trueCondition($Condition)){
            $this->ExecuteReactions($Reactions);
            return(true);
        } else{
            return(false);
        }
    }
    
    public function RunCondition() {
        $Condition = new \stdClass();
        $Condition->Object = "Trigger";
        $Condition->Field = "DemoField";
        $Condition->Value = "=Hello";
        return($this->trueCondition($Condition));
    }
    
}
