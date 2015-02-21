<?php

$Status = new \Franklin\Basics\Status($VirtualParent);

$Status->Id = $Data['Status_Id'];

$Results[] = $Status->Remove();
