<?php

$Topic = new \Franklin\Forum\Topic($VirtualParent);

$Topic->Id = $Data['Topic_Id'];

$Results[] = $Topic->Remove();
