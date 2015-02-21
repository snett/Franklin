<?php
$Translation = new \Franklin\Localization\Translation($VirtualParent);

//$Translation->Id = $Data['Translation_Id'];
$Translation->ElementType = $Data['Translation_ElementType'];
$Translation->ElementField = $Data['Translation_ElementField'];
$Translation->ElementId = $Data['Translation_ElementId'];
$Translation->Value = $Data['Translation_Value'];
$Translation->Status = new \Franklin\Basics\Status($Translation);
$Translation->Status->Id = $Data['Translation_Status_Id'];
$Translation->Language = new \Franklin\Data\Language($Translation);
$Translation->Language->Id = $Data['Translation_Language_Id'];
$Translation->UserTouched = new \Franklin\User\User($Translation);
$Translation->UserTouched->Id = $User->Id;

$Results[] = $Translation->Create();

