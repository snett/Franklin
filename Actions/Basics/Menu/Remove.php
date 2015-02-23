<?php

$Menu = new \Franklin\Component\Menu($VirtualParent);

$Menu->Id = $Data['Menu_Id'];

$Results[] = $Menu->Remove();
