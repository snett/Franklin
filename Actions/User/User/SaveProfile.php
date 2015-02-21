<?php

if ($User->ValidPass($Data['User_Password_Currect'])){
    $Results[] = 'Incorrect current password.';
} else if ($Data['User_Password'] !== $Data['User_Password_Again']){
    $Results[] = 'Password mismatch.';
} else if (strlen($Data['User_Password']) < 8){
    $Results[] = 'Passwords have to be at least 8 charachters length.';
} else{
    $User->Password = $User->Encode($Data['User_Password']);
    $User->Set();
    $Results[] = 'Password changed successfully. Please sign out and sign in again.';
    $Results['Success'] = true;
}
