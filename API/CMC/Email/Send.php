<?php

$Email = new \Franklin\CMC\Email($VirtualParent);

if (is_array($Data['CMC.Email.To'])){
    foreach ($Data['CMC.Email.To'] as $To) {
        $Email->To($To);
    }
} else{
    $Email->To($Data['CMC.Email.To']);
}

if (is_array($Data['CMC.Email.Cc'])){
    foreach ($Data['CMC.Email.Cc'] as $Cc) {
        $Email->Cc($Cc);
    }
} else{
    $Email->Cc($Data['CMC.Email.Cc']);
}

if (is_array($Data['CMC.Email.Bcc'])){
    foreach ($Data['CMC.Email.Bcc'] as $Bcc) {
        $Email->Bcc($Bcc);
    }
} else{
    $Email->Bcc($Data['CMC.Email.Bcc']);
}

$Email->Subject($Data['CMC.Email.Subject'])
      ->PlainBody($Data['CMC.Email.PlainBody'])
      ->HTMLBody($Data['CMC.Email.HTMLBody'])
      ->Send();

$Results['CMC.Email.Send']['Result'] = $Email->Result;
$Results['CMC.Email.Send']['LastDeliver'] = $Email->LastDeliver;
