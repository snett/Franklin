<?php

$User = new \Franklin\User\User($VirtualParent);

$Login = $User->Login($Data['email'], $Data['password']);

if ($Login !== true){
    $Results[] = "Franklin denied the access.";
} else{
    header("Location: " . $System->URL->Scheme . "://" . $System->URL->Host . "/Admin/" . $GetData['Location']);
}