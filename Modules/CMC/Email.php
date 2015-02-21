<?php

/**
 * Email Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\CMC;

class Email {
    
    public $Parent;
    public $System;
    public $LastDeliver;
    public $Result;
    private $Deliver;
    public $Data;
    private $Config;
    private $SafeMode;

    public function __construct($Parent, $Data = null) {
        $this->Parent = $Parent;
        $this->System = $Parent->System;
        $this->Config = new \stdClass();
        $this->Deliver = new \stdClass();
        if (!empty($Data)) {
            $this->Data = $Data;
        }
        $this->loadConfig();
    }

    public function enableSafeMode() {
        $this->SafeMode = true;
    }

    public function disableSafeMode() {
        $this->SafeMode = false;
    }

    public function Subject($Subject) {
        $this->Data->Subject = $Subject;
        return($this);
    }

    public function To($To) {
        $this->Data->To[] = $To;
        return($this);
    }

    public function Cc($Cc) {
        $this->Data->Cc[] = $Cc;
        return($this);
    }

    public function Bcc($Bcc) {
        $this->Data->Bcc[] = $Bcc;
        return($this);
    }

    public function Tag($Tag) {
        $this->Data->Tag = $Tag;
        return($this);
    }

    public function HTMLBody($HTML) {
        $this->Data->HTMLBody = $HTML;
        return($this);
    }

    public function PlainBody($Plain) {
        $this->Data->PlainBody = $Plain;
        return($this);
    }

    public function Body($HTML, $Plain) {
        $this->Data->HTMLBody = $HTML;
        $this->Data->PlainBody = $Plain;
        return($this);
    }

    public function AttachFile($Name, $FilePath) {
        $Content = file_get_contents($FilePath);
        $ContentType = mime_content_type($FilePath);
        $this->Attach($Name, $Content, $ContentType, $FilePath);
        return($this);
    }

    public function Attach($Name, $Content, $ContentType, $Path = null) {
        $this->Data->Attachments[$this->Data->AttachmentCount] = new \stdClass();
        $this->Data->Attachments[$this->Data->AttachmentCount]->Name = $Name;
        $this->Data->Attachments[$this->Data->AttachmentCount]->ContentType = $ContentType;
        $this->Data->Attachments[$this->Data->AttachmentCount]->Content = $Content;
        $this->Data->Attachments[$this->Data->AttachmentCount]->Path = $Path;
        if (!base64_decode($Content, true)) {
            $this->Data->Attachments[$this->Data->AttachmentCount]->ContentBase64 = base64_encode($Content);
        } else {
            $this->Data->Attachments[$this->Data->AttachmentCount]->ContentBase64 = $Content;
        }
        $this->Data->AttachmentCount++;
        return($this);
    }

    public function Send() {
        $this->Result = "Send Called";
        if ($this->SafeMode === true) {
            return($this->SendSafely());
        }
        switch ($this->Config->Deliver) {
            case 'postmark':
                $Result = $this->SendPostmark();
                break;
            default:
                $Result = $this->SendSMTP();
                break;
        }
        $this->Result = $Result;
        return($this);
    }

    public function SendSafely() {
        switch ($this->Config->Deliver) {
            case 'postmark':
                $Result = $this->SendPostmark();
                if ($Result !== true) {
                    $Result = $this->SendSMTP();
                }
                break;
            default:
                $Result = $this->SendSMTP();
                if ($Result !== true) {
                    $Result = $this->SendPostmark();
                }
                break;
        }
        $this->Result = $Result;
        return($this);
    }

    public function SendPostmark() {
        $this->LastDeliver = 'postmark';
        $this->Deliver->Postmark = new \Postmark($this->Config->Postmark->Token, $this->Config->Sender, $this->Config->ReplyTo);
        $this->Deliver->Postmark
                ->to($this->Data->To[0])
                ->cc($this->Data->Cc[0])
                ->bcc($this->Data->Bcc[0])
                ->subject($this->Data->Subject)
                ->html_message($this->Data->HTMLBody)
                ->plain_message($this->Data->PlainBody)
                ->tag($this->Data->Tag);
        foreach ($this->Data->Attachments as $Attachment) {
            $this->Deliver->Postmark->attachment($Attachment->Name, $Attachment->ContentBase64, $Attachment->ContentType);
        }
        return($this->Deliver->Postmark->send());
    }

    public function SendSMTP() {
        $this->LastDeliver = 'smtp';
        //SMTP Settings
        $this->Deliver->SMTP = new \PHPMailer();
        $this->Deliver->SMTP->isSMTP();
        $this->Deliver->SMTP->Host = $this->Config->SMTP->Host;
        $this->Deliver->SMTP->SMTPAuth = $this->Config->SMTP->Auth;
        $this->Deliver->SMTP->Username = $this->Config->SMTP->User;
        $this->Deliver->SMTP->Password = $this->Config->SMTP->Password;
        $this->Deliver->SMTP->SMTPSecure = $this->Config->SMTP->Secure;
        $this->Deliver->SMTP->Port = $this->Config->SMTP->Port;
        //Email settings
        $this->Deliver->SMTP->From = $this->Config->Sender;
        $this->Deliver->SMTP->FromName = $this->Config->SenderName;
        foreach ($this->Data->To as $To) {
            $this->Deliver->SMTP->addAddress($To);
        }
        foreach ($this->Data->Cc as $Cc) {
            $this->Deliver->SMTP->addCc($Cc);
        }
        foreach ($this->Data->Bcc as $Bcc) {
            $this->Deliver->SMTP->addBcc($Bcc);
        }
        $this->Deliver->SMTP->addReplyTo($this->Data->ReplyTo);
        foreach ($this->Data->Attachments as $Attachment) {
            $this->Deliver->SMTP->addAttachment($Attachment->Path, $Attachment->Name);
        }
        if (!empty($this->Data->HTMLBody)) {
            $this->Deliver->SMTP->isHTML(true);
            $this->Deliver->SMTP->Body = $this->Data->HTMLBody;
            $this->Deliver->SMTP->AltBody = $this->Data->PlainBody;
        } else {
            $this->Deliver->SMTP->Body = $this->Data->PlainBody;
        }
        $this->Deliver->SMTP->Subject = $this->Data->Subject;
        if (!$this->Deliver->SMTP->send()) {
            return($this->Deliver->SMTP->ErrorInfo);
        } else {
            return(true);
        }
    }

    public function loadConfig() {
        $ConfigFile = new \Franklin\IO\XML($this->System->ConfigFile['COM']);
        $this->Config->Deliver = strtolower((string) $ConfigFile->XML->General->Deliver);
        $this->Config->Sender = (string) $ConfigFile->XML->General->Sender;
        $this->Config->SenderName = (string) $ConfigFile->XML->General->SenderName;
        $this->Config->ReplyTo = (string) $ConfigFile->XML->General->ReplyTo;
        $this->Config->SafeMode = (boolean) $ConfigFile->XML->General->SafeMode;
        if ($this->Config->Deliver == "postmark") {
            $this->Config->Postmark = new \stdClass();
            $this->Config->Postmark->Token = (string) $ConfigFile->XML->Postmark->Token;
            $this->Config->Postmark->Inbound = (string) $ConfigFile->XML->Postmark->Inbound;
        }
        if ($this->Config->Deliver == "smtp") {
            $this->Config->SMTP = new \stdClass();
            $this->Config->SMTP->Host = (string) $ConfigFile->XML->SMTP->Host;
            $this->Config->SMTP->Auth = (boolean) $ConfigFile->XML->SMTP->Auth;
            $this->Config->SMTP->User = (string) $ConfigFile->XML->SMTP->User;
            $this->Config->SMTP->Password = (string) $ConfigFile->XML->SMTP->Password;
            $this->Config->SMTP->Secure = (string) $ConfigFile->XML->SMTP->Secure;
            $this->Config->SMTP->Port = (string) $ConfigFile->XML->SMTP->Port;
        }
    }

}
