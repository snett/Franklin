<?php

$Term = new \Franklin\Localization\Term($VirtualParent);

$Term->Id = $Data['Term_Id'];
$Term->Name = $Data['Term_Name'];
$Term->Status = new \Franklin\Basic\Status($Term);
$Term->Status->Id = $Data['Term_Status_Id'];
$Term->Content = $Data['Term_Content'];

$Results[] = $Term->Set();
