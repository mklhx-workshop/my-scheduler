<?php
//
// Fonction détection du User_Agent
//
Function UA_Detect($ua)
{
    if (preg_match('/iphone/i', $ua) || preg_match('/android/i', $ua) || preg_match('/blackberry/i', $ua)) {
        return 'mobile';
    } elseif (preg_match('/windows/i', $ua) || preg_match('/macintosh/i', $ua)) {
        return 'pc';
    } else {
        return 'unknown';
    }

}

//
// Connection to Database
//
function sqlConnection()
{
    // Connexion MySQL
    $db = mysqli_connect('127.0.0.1', 'root', 'root', 'planning_voiture', 33065);
    // Sélection BDD
    //mysqli_select_db($db,'planning_voiture');
    return $db;
}

function sqlClose($link)
{
    // Fermeture connexion mysql
    mysqli_close($link);
}

//
// Fonction Lecture de la mmémorisation variable $LockLogs
//
function sqlReadLockLogs()
{
    $link = sqlConnection();
    $sqlQuery = 'SELECT LockLogs FROM travail_planning ;';
    logFile('sqlReadLockLogs', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    $result = mysqli_fetch_assoc($sqlSend);
    foreach ($result as $value) {
        $val = $value;
    }
    sqlClose($link);
    return $val;
}

//
// Fonction ecriture de la variable $LockLogs
//
function sqlWriteLockLogs($value)
{
    $link = sqlConnection();
    $sqlQuery = 'UPDATE `travail_planning` SET `LockLogs` = "' . $value . '" ;';
    logFile('sqlWriteLockLogs', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    sqlClose($link);
}

//
// Fonction log
//
function logFile($name, $msg)
{
    global $LockLogs;
    if ($LockLogs == 1) {
        $lines = array();
        $lines = explode('|', $msg);
        $Fichier = fopen('./logs/' . $name . '.txt', 'a+');
        foreach ($lines as $line) {
            fputs($Fichier, date('Y-m-d H:i:s') . ' : ' . $line . "\n");
        }
        fclose($Fichier);
    }
}

//
// Connexion utilisateur
//
function sqlConnectUser($user, $pwd)
{
    $link = sqlConnection();
    $sqlQuery = 'SELECT login, pword, droits FROM users WHERE initiales = "' . $user . '";';
    logFile('sqlConnectUser', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    $result = mysqli_fetch_assoc($sqlSend);
    if (count($result) != 3) {
        logFile('sqlConnectUser', 'SQL Return feels bad; Number Result : ' . count($result) . ' ; Login : ' . $result['login'] . ' ; Rights : ' . $result['droits'] . ' ; Password is secret');
    } else {
        logFile('sqlConnectUser', 'SQL Return feels good; Number Result: ' . count($result) . ' ; Login : ' . $result['login'] . ' ; Rights: ' . $result['droits'] . ' ; Password is secret');
    }
    sqlClose($link);
    return $result;
}

//
// Communication / Notification
//
function notifyByEmail($initials, $title, $infos)
{
    ini_set("SMTP", "smtp.gmail.fr");
    ini_set("smtp_port", 25);
    $info = explode('|', $infos);
    //$msg = 'Véhicule '.$info[1].' réservé du '.$info[4].' '.$info[5].' au '.$info[6].' '.$info[7].' pour l\'intervention chez '.$info[2].' à '.$info[3];
    // On filtre les serveurs qui rencontrent des bogues.
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) {
        $passage_ligne = "\r\n";
    } else {
        $passage_ligne = "\n";
    }
    //=====Déclaration des messages au format texte et au format HTML.
    $message_txt = 'Véhicule ' . $info[1] . ' réservé du ' . $info[4] . ' ' . $info[5] . ' au ' . $info[6] . ' ' . $info[7] . ' pour l\'intervention chez ' . $info[2] . ' à ' . $info[3];
    $message_html = '<html><head></head><body>Véhicule ' . $info[1] . ' réservé du ' . $info[4] . ' ' . $info[5] . ' au ' . $info[6] . ' ' . $info[7] . ' pour l\'intervention chez ' . $info[2] . ' à ' . $info[3] . '</body></html>';
    //==========
    //====e=Création de la boundary
    $boundary = "-----=" . md5(rand());
    //==========
    //=====Définition du sujet.
    $sujet = $title;
    //=========
    //=====Création du header de l'e-mail.
    $header = "From: \"cyorleans.admin\"<cyorleans.admin@gmail.com>" . $passage_ligne;
    $header .= "Reply-to: \"cyorleans.admin\" <cyorleans.admin@gmail.com>" . $passage_ligne;
    $header .= "MIME-Version: 1.0" . $passage_ligne;
    $header .= "Content-Type: multipart/alternative;" . $passage_ligne . " boundary=\"$boundary\"" . $passage_ligne;
    //==========
    //=====Création du message.
    $message = $passage_ligne . "--" . $boundary . $passage_ligne;
    //=====Ajout du message au format texte.
    $message .= "Content-Type: text/plain; charset=\"ISO-8859-1\"" . $passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
    $message .= $passage_ligne . $message_txt . $passage_ligne;
    //==========
    $message .= $passage_ligne . "--" . $boundary . $passage_ligne;
    //=====Ajout du message au format HTML
    $message .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
    $message .= $passage_ligne . $message_html . $passage_ligne;
    //==========
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    //==========
    //=====Envoi de l'e-mail.
    //mail($mail,$sujet,$message,$header);
    //==========
    $link = sqlConnection();
    // Récupération de l'adresse Email
    $sqlQuery = 'SELECT email FROM users WHERE initiales="' . $initials . '"';
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    $adresse = mysqli_fetch_assoc($sqlSend);
    //foreach ($adresse as $mail){
    //$email = $mail;
    //}
    $email = 'mickael.lehoux@gmail.com';
    logFile('notifyByEmail', 'Header : ' . $header . ' | Destinataire : ' . $email . ' | Sujet : ' . $sujet . ' | Message : ' . $message);
    $return = mail($email, $sujet, $message, $header);
    logFile('notifyByEmail', 'Function Return : ' . $return);
    sqlClose($link);
    // return $result;
}

// function notifyBySms($phoneNum,$msg){
// if((strlen($phoneNum)<10 && strlen($phoneNum)>12) || (strlen($phoneNum)>10 && strlen($phoneNum)<12)){
// return 'Le numéro de téléphone n\'est pas ou est mal renseigné. Il doit comporter 10 numéro du type 0612345678 ou +33612345678';
// } else if (strlen($phoneNum) == 10) {
// $numToUse = '+33'.substr($phoneNum,strlen($phoneNum)-9);
// } else if (strlen($phoneNum) == 12) {
// $numToUse = $phoneNum;
// }
// echo $numToUse.'<br/>';
// echo $msg.'<br/>';
// $aProviders = array('orange.net','sfr.fr','mms.bouyguestelecom.fr','vmobl.com','vtext.com', 'tmomail.net', 'txt.att.net', 'mobile.pinger.com', 'page.nextel.com');
// foreach ($aProviders as $sProvider){
// echo $sProvider.'<br/>';
// if(mail($numToUse . '@' . $sProvider,'', $msg)){
//C'est bon, l'SMS a correctement été envoyé avec le fournissuer
// return 'true';
// break;
// } else {
//L'envoi de l'SMS a échoué avec le fournisseur, nous en essayons un autre dans la liste $aProviders
// continue;
// }
// }
// }
//
// Function Check Alpha
//
function check_Alpha($str)
{
    preg_match('/([^A-Za-z])/', $str, $result);
    //On cherche tt les caractères autre que [A-z]
    if (!empty($result)) {//si on trouve des caractère autre que A-z
        return false;
    }
    return true;
}

//
// Function Check Num
//
function check_Num($str)
{
    preg_match("/([^0-9])/", $str, $result);
    //On cherche tt les caractères autre que ou [0-9]
    if (!empty($result)) {//si on trouve des caractère autre que 0-9
        return false;
    }
    return true;
}

//
// Function Check AlphaNum
//
function check_AlphaNum($str)
{
    preg_match("/([^A-Za-z0-9])/", $str, $result);
    //On cherche tt les caractères autre que [A-Za-z] ou [0-9]
    if (!empty($result)) {//si on trouve des caractère autre que A-Za-z ou 0-9
        return false;
    }
    return true;
}

//
// Functions SQL Select
//
function sqlSearchCarsEvents($start, $end, $carimmat, $user)
{
    $arg = !empty($start) + !empty($end) + !empty($carimmat) + !empty($user);
    if ($arg == 1) {
        if (!empty($start)) {
            $sqlQuery = 'date_start >= "' . $start . '"';
        } else if (!empty($end)) {
            $sqlQuery = 'date_end <= "' . $end . '"';
        } else if (!empty($carimmat)) {
            $sqlQuery = 'vehicule_immat = "' . $carimmat . '"';
        } else if (!empty($user)) {
            $sqlQuery = 'utilisateur = "' . $user . '"';
        }
    } else {
        $sqlQuery = !empty($start) ? ' date_start >= "' . $start . '"' : '';
        $sqlQuery .= !empty($end) ? ' AND date_end <= "' . $end . '"' : '';
        if (empty($start) && empty($end) && !empty($carimmat)) {
            $sqlQuery .= ' vehicule_immat = "' . $carimmat . '"';
        } else if (!empty($start) && !empty($end) && !empty($carimmat)) {
            $sqlQuery .= ' AND vehicule_immat = "' . $carimmat . '"';
        }
        $sqlQuery .= !empty($user) ? ' AND utilisateur = "' . $user . '"' : '';
    }
    $link = sqlConnection();
    $sqlStart = 'SELECT * FROM planning_voitures WHERE ';
    $sqlQueryEnd = ' ORDER by id_events;';
    logFile('sqlSearchCarsEvents', 'SQL Query : ' . $sqlStart . $sqlQuery . $sqlQueryEnd);
    $sqlSend = mysqli_query($link, $sqlStart . $sqlQuery . $sqlQueryEnd) or die('Erreur SQL !<br>' . $sqlStart . $sqlQuery . $sqlQueryEnd . '<br>' . mysqli_error($link));;
    while ($history = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $history;
        $logmsg = 'id_events : ' . $history['id_events'] . ' , horodatage : ' . $history['horodatage'] . ' , date_start : ' . $history['date_start'] . ' , am_pm_start : ' . $history['am_pm_start'];
        $logmsg .= ' , date_end : ' . $history['date_end'] . ' , am_pm_end : ' . $history['am_pm_end'] . ' , client : ' . $history['client'] . ' , ville : ' . $history['ville'] . ' , utilisateur : ' . $history['utilisateur'];
        $logmsg .= ' , vehicule_modele : ' . $history['vehicule_modele'] . ' , vehicule_immat : ' . $history['vehicule_immat'] . ', commentaire : ' . $history['commentaire'];
        logFile('sqlSearchCarsEvents', 'SQL Return : ' . $logmsg);
    }
    sqlClose($link);
    // Return Array
    if (!empty($result)) {
        return $result;
    } else {
        return 'false';
    }
}

function sqlEventCarsExist($carimmat, $carmodele, $start, $end, $am_pm_start, $am_pm_end, $id = '')
{
    $link = sqlConnection();
    if (empty($id)) {
        $sqlQuery = 'SELECT * FROM planning_voitures WHERE vehicule_immat = "' . $carimmat . '" AND ';
        $sqlQuery .= '(((date_start < "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start <="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_end > "' . $start . '" OR (date_end = "' . $start . '" AND am_pm_end >="' . $am_pm_start . '")))';

        $sqlQuery .= 'OR ((date_start > "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start >="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_start < "' . $end . '" OR (date_start = "' . $end . '" AND am_pm_start <="' . $am_pm_end . '"))));';
        //$sqlQuery = 'SELECT * FROM planning_voitures WHERE vehicule_modele = "'.$carmodele.'" AND vehicule_immat = "'.$carimmat.'" AND ((date_start >= "'.$start.'" AND date_start <= "'.$end.'") OR (date_end >= "'.$start.'" AND date_end <= "'.$end.'")) AND am_pm_start = "'.$am_pm_start.'" AND am_pm_end = "'.$am_pm_end.'";';
    } else {
        $sqlQuery = 'SELECT * FROM planning_voitures WHERE vehicule_immat = "' . $carimmat . '" AND ';
        $sqlQuery .= '(((date_start < "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start <="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_end > "' . $start . '" OR (date_end = "' . $start . '" AND am_pm_end >="' . $am_pm_start . '")))';

        $sqlQuery .= 'OR ((date_start > "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start >="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_start < "' . $end . '" OR (date_start = "' . $end . '" AND am_pm_start <="' . $am_pm_end . '"))))';
        $sqlQuery .= 'AND id_events <> "' . $id . '";';
        //$sqlQuery = 'SELECT * FROM planning_voitures WHERE vehicule_modele = "'.$carmodele.'" AND vehicule_immat = "'.$carimmat.'" AND ((date_start >= "'.$start.'" AND date_start <= "'.$end.'") OR (date_end >= "'.$start.'" AND date_end <= "'.$end.'")) AND am_pm_start = "'.$am_pm_start.'" AND am_pm_end = "'.$am_pm_end.'" AND id_events <> "'.$id.'";';
    }
    logFile('sqlEventCarsExist', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    if (mysqli_num_rows($sqlSend)) {
        logFile('sqlEventCarsExist', 'SQL Return : true');
        $result = true;
    } else {
        logFile('sqlEventCarsExist', 'SQL Return : false');
        $result = false;
    }
    sqlClose($link);
    return $result;
}

function sqlSearchDevicesEvents($start, $end, $deviceid, $user)
{
    $arg = !empty($start) + !empty($end) + !empty($deviceid) + !empty($user);
    logFile('sqlSearchDevicesEvents', 'Nombre d\'arguments de la fonction : ' . $arg);
    if ($arg == 1) {
        if (!empty($start)) {
            $sqlQuery = 'date_start >= "' . $start . '"';
        } else if (!empty($end)) {
            $sqlQuery = 'date_end <= "' . $end . '"';
        } else if (!empty($deviceid)) {
            $sqlQuery = 'appareil_id = "' . $deviceid . '"';
        } else if (!empty($user)) {
            $sqlQuery = 'utilisateur = "' . $user . '"';
        }
    } else {
        $sqlQuery = !empty($start) ? ' date_start >= "' . $start . '"' : '';
        $sqlQuery .= !empty($end) ? ' AND date_end <= "' . $end . '"' : '';
        if (empty($start) && empty($end) && !empty($deviceid)) {
            $sqlQuery .= ' appareil_id = "' . $deviceid . '"';
        } else if (!empty($start) && !empty($end) && !empty($deviceid)) {
            $sqlQuery .= ' AND appareil_id = "' . $deviceid . '"';
        }
        $sqlQuery .= !empty($user) ? ' AND utilisateur = "' . $user . '"' : '';
    }
    $link = sqlConnection();
    $sqlStart = 'SELECT * FROM planning_devices WHERE ';
    $sqlQueryEnd = ' ORDER by id_events;';
    logFile('sqlSearchDevicesEvents', 'SQL Query : ' . $sqlStart . $sqlQuery . $sqlQueryEnd);
    $sqlSend = mysqli_query($link, $sqlStart . $sqlQuery . $sqlQueryEnd) or die('Erreur SQL !<br>' . $sqlStart . $sqlQuery . $sqlQueryEnd . '<br>' . mysqli_error($link));;
    while ($history = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $history;
        $logmsg = 'id_events : ' . $history['id_events'] . ' | horodatage : ' . $history['horodatage'] . ' | date_start : ' . $history['date_start'] . ' | am_pm_start : ' . $history['am_pm_start'];
        $logmsg .= ' | date_end : ' . $history['date_end'] . ' | am_pm_end : ' . $history['am_pm_end'] . ' | client : ' . $history['client'] . ' | ville : ' . $history['ville'] . ' | utilisateur : ' . $history['utilisateur'];
        $logmsg .= ' | appareil_modele : ' . $history['appareil_modele'] . ' | appareil_id : ' . $history['appareil_id'] . ' | commentaire : ' . $history['commentaire'];
        logFile('sqlSearchDevicesEvents', 'SQL Return : ' . $logmsg);
    }
    // Return Array
    if (empty($result)) {
        $result = 'false';
    }
    sqlClose($link);
    return $result;
}

function sqlEventDevicesExist($deviceid, $devicemodele, $start, $end, $am_pm_start, $am_pm_end, $id = '')
{
    $link = sqlConnection();
    if (empty($id)) {
        $sqlQuery = 'SELECT * FROM planning_devices WHERE appareil_id = "' . $deviceid . '" AND ';
        $sqlQuery .= '(((date_start < "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start <="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_end > "' . $start . '" OR (date_end = "' . $start . '" AND am_pm_end >="' . $am_pm_start . '")))';

        $sqlQuery .= 'OR ((date_start > "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start >="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_start < "' . $end . '" OR (date_start = "' . $end . '" AND am_pm_start <="' . $am_pm_end . '"))));';
        //$sqlQuery = 'SELECT * FROM planning_devices WHERE appareil_modele = "'.$devicemodele.'" AND appareil_id = "'.$deviceid.'" AND ((date_start >= "'.$start.'" AND date_start <= "'.$end.'") OR (date_end >= "'.$start.'" AND date_end <= "'.$end.'"));';// AND (am_pm_start = "'.$am_pm_start.'" AND am_pm_end = "'.$am_pm_end.'");';
    } else {
        $sqlQuery = 'SELECT * FROM planning_devices WHERE appareil_id = "' . $deviceid . '" AND ';
        $sqlQuery .= '(((date_start < "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start <="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_end > "' . $start . '" OR (date_end = "' . $start . '" AND am_pm_end >="' . $am_pm_start . '")))';

        $sqlQuery .= 'OR ((date_start > "' . $start . '" OR (date_start = "' . $start . '" AND am_pm_start >="' . $am_pm_start . '"))';
        $sqlQuery .= 'AND (date_start < "' . $end . '" OR (date_start = "' . $end . '" AND am_pm_start <="' . $am_pm_end . '"))))';
        $sqlQuery .= 'AND id_events <> "' . $id . '";';
        //$sqlQuery = 'SELECT * FROM planning_devices WHERE appareil_modele = "'.$devicemodele.'" AND appareil_id = "'.$deviceid.'" AND ((date_start >= "'.$start.'" AND date_start <= "'.$end.'") OR (date_end >= "'.$start.'" AND date_end <= "'.$end.'")) AND id_events <> "'.$id.'";';//AND am_pm_start = "'.$am_pm_start.'" AND am_pm_end = "'.$am_pm_end.'"
    }
    logFile('sqlEventDevicesExist', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    if (mysqli_num_rows($sqlSend)) {
        logFile('sqlEventDevicesExist', 'SQL Return : true');
        $result = true;
    } else {
        logFile('sqlEventDevicesExist', 'SQL Return : false');
        $result = false;
    }
    sqlClose($link);
    return $result;
}

function sqlSelectUsers()
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT nom,prenom,initiales,email,entreprise,telephone FROM users';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in Array
    while ($users = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $users;
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectCars()
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT modele,immatriculation FROM cars';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in Array
    while ($cars = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $cars;
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectCustomers()
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT DISTINCT client FROM planning_voitures';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlSend . '<br>' . mysqli_error($link));
    // Put it in Array
    while ($customers = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $customers['client'];
    }
    sqlClose($link);
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectUsersByInitials($initials)
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT * FROM users WHERE initiales = "' . $initials . '"';
    logFile('sqlSelectUsersByInitials', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlSend . '<br>' . mysqli_error($link));
    // Put it in Array
    $result = mysqli_fetch_assoc($sqlSend);
    foreach ($result as $line => $val) {
        logFile('sqlSelectUsersByInitials', 'SQL Return : ' . $val);
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectCarsByImmat($immat)
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT * FROM cars WHERE immatriculation = "' . $immat . '"';
    logFile('sqlSelectCarsByImmat', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlSend . '<br>' . mysqli_error($link));
    // Put it in Array
    $result = mysqli_fetch_assoc($sqlSend);
    foreach ($result as $line => $val) {
        logFile('sqlSelectCarsByImmat', 'SQL Return : ' . $val);
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectEventDevicesById($id)
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT * FROM planning_devices WHERE id_events = "' . $id . '"';
    logFile('sqlSelectEventDevicesById', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlSend . '<br>' . mysqli_error($link));
    // Put it in Array
    $result = mysqli_fetch_assoc($sqlSend);
    foreach ($result as $line => $val) {
        logFile('sqlSelectEventDevicesById', 'SQL Return : ' . $val);
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectCities()
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT DISTINCT ville FROM planning_voitures';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in Array
    while ($cities = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $cities['ville'];
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectEventsCars($start, $end)
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT * FROM planning_voitures WHERE ((date_start <= "' . $start . '" AND date_end >= "' . $start . '") OR (date_start >= "' . $start . '" AND date_start <= "' . $end . '"))';
    logFile('sqlSelectEventsCars', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in Array
    while ($events = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $events;
        $logmsg = 'id_events : ' . $events['id_events'] . ' | horodatage : ' . $events['horodatage'] . ' | date_start : ' . $events['date_start'] . ' | am_pm_start : ' . $events['am_pm_start'];
        $logmsg .= ' | date_end : ' . $events['date_end'] . ' | am_pm_end : ' . $events['am_pm_end'] . ' | client : ' . $events['client'] . ' | ville : ' . $events['ville'] . ' | utilisateur : ' . $events['utilisateur'];
        $logmsg .= ' | vehicule_modele : ' . $events['vehicule_modele'] . ' | vehicule_immat : ' . $events['vehicule_immat'] . ' | commentaire : ' . $events['commentaire'];
        logFile('sqlSelectEventsCars', 'SQL Return : ' . $logmsg);
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectEventsCarsById($id)
{
    // Sql Query
    $sqlQuery = 'SELECT * FROM planning_voitures WHERE id_events ="' . $id . '"';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in result
    $result = mysqli_fetch_assoc($sqlSend);
    // Return result
    return $result;
}

function sqlSelectEventsDevicesById($id)
{
    // Sql Query
    $sqlQuery = 'SELECT * FROM planning_devices WHERE id_events ="' . $id . '"';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in result
    $result = mysqli_fetch_assoc($sqlSend);
    // Return result
    return $result;
}

function sqlSelectDevices()
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'SELECT * FROM devices ORDER BY "identifiant" ASC';
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in Array
    while ($devices = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $devices;
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sqlSelectEventsDevices($start, $end)
{
    $link = sqlConnection();
    $result = array();
    //Sql Query
    $sqlQuery = 'SELECT * FROM planning_devices WHERE ((date_start <= "' . $start . '" AND date_end >= "' . $start . '") OR (date_start >= "' . $start . '" AND date_start <= "' . $end . '"))';
    logFile('sqlSelectEventsDevices', 'SQL Query : ' . $sqlQuery);
    //Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    //Put it in Array
    while ($devices = mysqli_fetch_assoc($sqlSend)) {
        $result[] = $devices;
        $logmsg = 'id_events : ' . $devices['id_events'] . ' , horodatage : ' . $devices['horodatage'] . ' , date_start : ' . $devices['date_start'] . ' , am_pm_start : ' . $devices['am_pm_start'];
        $logmsg .= ' , date_end : ' . $devices['date_end'] . ' , am_pm_end : ' . $devices['am_pm_end'] . ' , client : ' . $devices['client'] . ' , ville : ' . $devices['ville'] . ' , utilisateur : ' . $devices['utilisateur'];
        $logmsg .= ' , appareil_modele : ' . $devices['appareil_modele'] . ' , appareil_id : ' . $devices['appareil_id'] . ' , commentaire : ' . $devices['commentaire'];
        logFile('sqlSelectEventsDevices', 'SQL Return : ' . $logmsg);
    }
    sqlClose($link);
    //Return Array
    return $result;
}

function sqlSelectDeviceById($device)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'SELECT * FROM devices WHERE identifiant ="' . $device . '"';
    logFile('sqlSelectDeviceById', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in result
    $result = mysqli_fetch_assoc($sqlSend);
    foreach ($result as $line) {
        logFile('sqlSelectDeviceById', 'SQL Return : ' . $line);
    }
    sqlClose($link);
    // Return result
    return $result;
}

//
// Functions SQL Insert
//
function sqlInsertEventsCars($user, $carmodele, $carimmat, $customer, $city, $start, $end, $am_pm_start, $am_pm_end, $comment = ' ')
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'INSERT INTO planning_voitures (date_start,am_pm_start,date_end,am_pm_end,client,ville,utilisateur,vehicule_modele,vehicule_immat,commentaire) VALUE ("' . $start . '","' . $am_pm_start . '","' . $end . '","' . $am_pm_end . '","' . $customer . '","' . $city . '","' . $user . '","' . $carmodele . '","' . $carimmat . '","' . $comment . '");';
    logFile('sqlInsertEventsCars', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlInsertEventsCars', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlInsertEventsDevices($user, $devicemodele, $deviceid, $customer, $city, $start, $end, $am_pm_start, $am_pm_end, $comment = ' ')
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'INSERT INTO planning_devices (date_start,am_pm_start,date_end,am_pm_end,client,ville,utilisateur,appareil_modele,appareil_id,commentaire) VALUE ("' . $start . '","' . $am_pm_start . '","' . $end . '","' . $am_pm_end . '","' . $customer . '","' . $city . '","' . $user . '","' . $devicemodele . '","' . $deviceid . '","' . $comment . '");';
    logFile('sqlInsertEventsDevices', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlInsertEventsDevices', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlInsertUsers($name, $firstname, $initials, $email, $entity, $phone, $right, $login, $pwd)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'INSERT INTO users (nom,prenom,initiales,email,entreprise,telephone,droits,login,pword) VALUE ("' . $name . '", "' . $firstname . '","' . $initials . '","' . $email . '","' . $entity . '","' . $phone . '","' . $right . '","' . $login . '","' . $pwd . '");';
    logFile('sqlInsertUsers', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlInsertUsers', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlInsertCars($marque, $modele, $immatriculation)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'INSERT INTO `cars` (marque,modele,immatriculation) VALUE ("' . $marque . '", "' . $modele . '", "' . $immatriculation . '");';
    logFile('sqlInsertCars', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlInsertCars', 'SQL Return : true, véhicule créé.');
    sqlClose($link);
    return $sqlSend;
}

function sqlInsertDevices($marque, $modele, $identifiant)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'INSERT INTO `devices` (marque,modele,identifiant) VALUE ("' . $marque . '", "' . $modele . '", "' . $identifiant . '");';
    logFile('sqlInsertDevices', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    if ($sqlSend) {
        logFile('sqlInsertDevices', 'SQL Return : true, appareil créé.');
    } else {
        logFile('sqlInsertDevices', 'SQL Return : false, appareil non créé.');
    }
    sqlClose($link);
}

//
// Function SQL Update
//
function sqlUpdateEventsCars($id, $user, $carmodele, $carimmat, $customer, $city, $start, $end, $am_pm_start, $am_pm_end, $comment = ' ')
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'UPDATE planning_voitures SET date_start ="' . $start . '", am_pm_start = "' . $am_pm_start . '", date_end = "' . $end . '", am_pm_end = "' . $am_pm_end . '", client ="' . $customer . '", ville = "' . $city . '", utilisateur = "' . $user . '", vehicule_modele = "' . $carmodele . '", vehicule_immat = "' . $carimmat . '", commentaire = "' . $comment . '" WHERE id_events = "' . $id . '";';
    //$sqlQuery = 'CALL sp_UPDATE_EVENT("'.$id.'","'.$user.'","'.$carmodele.'","'.$carimmat.'","'.$customer.'","'.$city.'","'.$start.'","'.$end.'","'.$am_pm_start.'","'.$am_pm_end.'","'.$comment.'");';
    logFile('sqlUpdateEventsCars', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlUpdateEventsCars', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlUpdateEventsDevices($id, $user, $devicemodele, $deviceid, $customer, $city, $start, $end, $am_pm_start, $am_pm_end, $comment = ' ')
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'UPDATE planning_devices SET date_start ="' . $start . '", am_pm_start = "' . $am_pm_start . '", date_end = "' . $end . '", am_pm_end = "' . $am_pm_end . '", client ="' . $customer . '", ville = "' . $city . '", utilisateur = "' . $user . '", appareil_modele = "' . $devicemodele . '", appareil_id = "' . $deviceid . '", commentaire = "' . $comment . '" WHERE id_events = "' . $id . '";';
    logFile('sqlUpdateEventsDevices', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlUpdateEventsDevices', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlUpdateUsers($name, $firstname, $initials, $email, $entity, $phone, $right, $login, $pwd)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'UPDATE users SET nom ="' . $name . '", prenom = "' . $firstname . '", email = "' . $email . '", entreprise ="' . $entity . '", telephone = "' . $phone . '", droits = "' . $right . '", login = "' . $login . '", pword = "' . $pwd . '" WHERE initiales = "' . $initials . '";';
    logFile('sqlUpdateUsers', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlUpdateUsers', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlUpdateCars($mark, $model, $immat)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'UPDATE cars SET marque = "' . $mark . '", modele ="' . $model . '" WHERE immatriculation = "' . $immat . '";';
    logFile('sqlUpdateCars', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlUpdateCars', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlUpdateDevices($mark, $model, $id)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'UPDATE devices SET marque = "' . $mark . '", modele ="' . $model . '" WHERE identifiant = "' . $id . '";';
    logFile('sqlUpdateDevices', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    //logFile('sqlUpdateDevices','SQL Return : '.$sqlSend);
    sqlClose($link);
}

//
// Function SQL Delete
//
function sqlDeleteEventsCars($id)
{
    $link = sqlConnection();
    //$sqlQuery = 'CALL sp_DELETE_EVENT("'.$id.'");';
    $sqlQuery = 'DELETE FROM planning_voitures WHERE id_events = "' . $id . '";';
    logFile('sqlDeleteEventsCars', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlUpdateDevices', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlDeleteEventsDevices($id)
{
    $link = sqlConnection();
    $sqlQuery = 'DELETE FROM planning_devices WHERE id_events = "' . $id . '";';
    logFile('sqlDeleteEventsDevices', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlDeleteEventsDevices', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlDeleteCars($immat)
{
    $link = sqlConnection();
    $sqlQuery = 'DELETE FROM cars WHERE immatriculation = "' . $immat . '";';
    logFile('sqlDeleteCars', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlDeleteCars', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlDeleteUsers($initials)
{
    $link = sqlConnection();
    $sqlQuery = 'DELETE FROM users WHERE initiales = "' . $initials . '";';
    logFile('sqlDeleteUsers', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlDeleteUsers', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

function sqlDeleteDevices($id)
{
    $link = sqlConnection();
    $sqlQuery = 'DELETE FROM devices WHERE identifiant = "' . $id . '";';
    logFile('sqlDeleteDevices', 'SQL Query : ' . $sqlQuery);
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    logFile('sqlDeleteDevices', 'SQL Return : ' . $sqlSend);
    sqlClose($link);
}

//
// #########################################################	CARNETS DE BORD		#########################################################
//
function sp_CHECK_DATAS_CB($immat)
{
    $link = sqlConnection();
    $result = array();
    // Sql Query
    $sqlQuery = 'CALL `sp_CHECK_DATAS_CB`("' . $immat . '");';
    logFile('sp_CHECK_DATAS_CB', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    // Put it in Array
    $result = mysqli_fetch_assoc($sqlSend);
    foreach ($result as $line => $val) {
        logFile('sp_CHECK_DATAS_CB', 'SQL Return : ' . $val);
    }
    sqlClose($link);
    // Return Array
    return $result;
}

function sp_INSERT_DATAS_CB($newIndex, $num, $idEvent, $lastIndex, $immat)
{
    $link = sqlConnection();
    // Sql Query
    $sqlQuery = 'CALL `sp_INSERT_DATAS_CB`("' . $newIndex . '","' . $num . '","' . $idEvent . '","' . $lastIndex . '","' . $immat . '");';
    logFile('sp_INSERT_DATAS_CB', 'SQL Query : ' . $sqlQuery);
    // Send Query
    $sqlSend = mysqli_query($link, $sqlQuery) or die('Erreur SQL !<br>' . $sqlQuery . '<br>' . mysqli_error($link));
    sqlClose($link);
}
