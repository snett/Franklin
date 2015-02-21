<?php

$Box = new \Franklin\Basics\Box($VirtualParent);

$Box->Id = $Data['Box_Id'];

$Results[] = $Box->Remove();
