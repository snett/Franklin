<?php

$Status = new \Franklin\Basic\Status($VirtualParent);

$Status->Id = $Data['Status_Id'];
$Status->Name = $Data['Status_Name'];
$Status->Element = $Data['Status_Element'];

$Results[] = $Status->Set();
