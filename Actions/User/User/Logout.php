<?php

$User = new \Franklin\User\User($VirtualParent);

$Login = $User->Logout();

header("Location: " . $System->URL->Scheme . "://" . $System->URL->Host . "/Admin/");