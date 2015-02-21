<?php

$NoError = false;
$Results['Forum.Comment.Send'] = array();
$LC = $Data['Language'];

/*HU*/
$Msg['hu']['success'] = "Hozzászólását sikeresen beküldte! Jóváhagyás után megjelenik.";
$Msg['hu']['incorrect_mail'] = "Helytelen e-mail cím. Helyes minta: xxx@yyy.zz";
$Msg['hu']['empty_field'] = 'Kérjük töltse ki a "#field#" mezőt!';
$Field['hu']['email'] = "E-mail cím";
$Field['hu']['name'] = "Név";
$Field['hu']['comment'] = "Hozzászólás";
/*SK*/
$Msg['sk']['success'] = "Váš komentár bol úspešne odoslaný! Po schválení sa zobrazí.";
$Msg['sk']['incorrect_mail'] = "Nesprávna e-mailová adresa. Správna ukážka: xxx@yyy.zz";
$Msg['sk']['empty_field'] = 'Prosím vyplňte políčko "#field#"!';
$Field['sk']['email'] = "E-mailová adresa";
$Field['sk']['name'] = "Meno";
$Field['sk']['comment'] = "Komentár";
/*CZ*/
$Msg['cz']['success'] = "Váš komentář byl úspěšně odeslán! Po schválení se zobrazí.";
$Msg['cz']['incorrect_mail'] = "Nesprávná e-mailová adresa. Správná ukázka: xxx@yyy.zz";
$Msg['cz']['empty_field'] = 'Prosím vyplňte políčko "#field#"!';
$Field['cz']['email'] = "E-mailová adresa";
$Field['cz']['name'] = "Jméno";
$Field['cz']['comment'] = "Komentář";
/*PL*/
$Msg['pl']['success'] = "Twoja wiadomość została pomyślnie wysłana! Po zatwierdzeniu pojawi się.";
$Msg['pl']['incorrect_mail'] = "Nieprawidłowy adres e-mailowy. Wiadomość przykład: xxx@yyy.zz";
$Msg['pl']['empty_field'] = 'Prosimy uzupełnić pole "#field#"!';
$Field['pl']['email'] = "Adres e-mailowy";
$Field['pl']['name'] = "Imię";
$Field['pl']['comment'] = "Komentarz";
/*IT*/
$Msg['it']['success'] = "Abbiamo ricevuto il suo commento! Una volta approvato apparirà.";
$Msg['it']['incorrect_mail'] = "Indirizzo e-mail non corretto. Esempio: xxx@yyy.zz";
$Msg['it']['empty_field'] = 'La preghiamo di compilare i campi "#field#"!';
$Field['it']['email'] = "Indirizzo e-mail";
$Field['it']['name'] = "Nome";
$Field['it']['comment'] = "Commento";
/*DE*/
$Msg['de']['success'] = "Ihr Kommentar ist angekommen! Nach Ihrer Zustimmung wird er veröffentlicht werden.";
$Msg['de']['incorrect_mail'] = "Falsche E-Mail-Adresse: Beispiel für eine richtige E-Mail-Adresse: xxx@yyy.zz";
$Msg['de']['empty_field'] = 'Füllen Sie bitte Feld "#field#" aus!';
$Field['de']['email'] = "E-Mail-Adresse";
$Field['de']['name'] = "Name";
$Field['de']['comment'] = "Kommentar";
/*FR*/
$Msg['fr']['success'] = "Commentaire bien envoyé! Il sera publié après l’approbation.";
$Msg['fr']['incorrect_mail'] = "Adresse e-mail incorrecte. Forme correcte: xxx@yyy.zz";
$Msg['fr']['empty_field'] = 'Vous êtes demandé/e de remplir la rubrique "#field#"!';
$Field['fr']['email'] = "Adresse e-mail";
$Field['fr']['name'] = "Nom";
$Field['fr']['comment'] = "Commentaire";

if (empty($Data['User_Email'])){
    $Error[] = str_replace("#field#", $Field[$LC]['email'], $Msg[$LC]['empty_field']);
}
if (empty($Data['User_Email']) || filter_var($Data['User_Email'], FILTER_VALIDATE_EMAIL) === false){
    $Error[] = $Msg[$LC]['incorrect_mail'];
}
if (empty($Data['User_Name'])){
    $Error[] = str_replace("#field#", $Field[$LC]['name'], $Msg[$LC]['empty_field']);
}
if (empty($Data['Forum_Comment'])){
    $Error[] = str_replace("#field#", $Field[$LC]['comment'], $Msg[$LC]['empty_field']);
}

if (empty($Error)){
    $NoError = true;
}

if ($NoError){
    $CommentObject = new \Franklin\Forum\Comment($VirtualParent);
    
    $CommentObject->ParentType = "";
    $CommentObject->Parent = $Data['Parent'] ? $Data['Parent'] : 0;
    $CommentObject->User->Id = 0;
    $CommentObject->Group->Id = (int) $Data['Group'];
    
    $CommentObject->TimeCreated = date("Y-m-d H:i:s");

    $CommentObject->Site->LoadByCode($Data['Site']);

    $CommentObject->Status->Id = 0;

    $CommentObject->UserName = $Data['User_Name'];
    $CommentObject->UserEmail = $Data['User_Email'];
    $CommentObject->Content = $Data['Forum_Comment'];

    if ($CommentObject->Create() === true){
        $Results['Forum.Comment.Send']['Status'] = $Msg[$LC]['success'];
        $Results['Forum.Comment.Send']['StatusCode'] = 200;
    } else{
        $Results['Forum.Comment.Send']['Status'] = str_replace("#field#", $Field[$LC]['comment'], $Msg[$LC]['empty_field']);
        $Results['Forum.Comment.Send']['StatusCode'] = 500;
    }
} else{
    $Results['Forum.Comment.Send']['Status'] = $Error[0];
    $Results['Forum.Comment.Send']['StatusCode'] = 500;
}