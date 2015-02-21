<?php

$Topic = new \Franklin\Forum\Topic($VirtualParent);

$Topic->TimeCreated = date("Y-m-d H:i:s");
$Topic->Name = $Data['Topic_Name'];
$Topic->Language->Id = $Data['Topic_Language_Id'];
$Topic->UserTouched = new \Franklin\User\User($Topic);
$Topic->UserTouched->Id = $User->Id;

$Results[] = $Topic->Set();
