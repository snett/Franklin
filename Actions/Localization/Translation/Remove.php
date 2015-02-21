<?php

$Translation = new \Franklin\Localization\Translation($VirtualParent);

$Translation->Id = $Data['Translation_Id'];

$Results[] = $Translation->Remove();
