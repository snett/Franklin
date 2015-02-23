<?php

$Status = new \Franklin\Basic\Status($VirtualParent);

$Status->Id = $Data['Status_Id'];

$Results[] = $Status->Remove();
