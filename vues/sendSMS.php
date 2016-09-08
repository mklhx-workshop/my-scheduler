<?php 
$sPhoneNum = '**********'; // Le numéro de téléphone qui recevra l'SMS (avec le préfixe, ex: +33)
$aProviders = array('vtext.com', 'tmomail.net', 'txt.att.net', 'mobile.pinger.com', 'page.nextel.com');
foreach ($aProviders as $sProvider)
{
    if(mail($sPhoneNum . '@' . $sProvider . '.com', '', 'Ce texto a été envoyé avec PHP, tout simplement !'))
    {
        // C'est bon, l'SMS a correctement été envoyé avec le fournissuer
        break;
    }
    else
    {
        // L'envoi de l'SMS a échoué avec le fournisseur, nous en essayons un autre dans la liste $aProviders
        continue; 
    }
}
?>
<?php 
/*
 * Fichier texte contenant les numéros de téléphone. Un numéro par ligne, car la séparation de ceux-ci est le "retour à la ligne".
 */
$rNumList = file_get_contents('phone_number_list.txt'); 

$aPhoneNums = explode("\r", $rNumList); // On met tous les numéros dans un tableau
foreach ($aPhoneNums as $sPhoneNum)
{
    sendSMS($sPhoneNum);
}

function sendSMS($sPhoneNum)
{
    $aProviders = array('vtext.com', 'tmomail.net', 'txt.att.net', 'mobile.pinger.com', 'page.nextel.com');

    foreach ($aProviders as $sProvider)
    {
        if(mail($sPhoneNum . '@' . $sProvider . '.com', '', 'Ce texto a été envoyé avec PHP, tout simplement !'))
        {
             // C'est bon, l'SMS a correctement été envoyé avec le fournissuer
             break;
         }
         else
         {
                 // L'envoi de l'SMS a échoué avec le fournisseur, nous en essayons un autre dans la liste $aProviders
                 continue; 
         }
    }
}
?>