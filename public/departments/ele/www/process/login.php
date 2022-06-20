<?php

require 'base.inc';
require BASE . '/../config.inc';

// http://ltb-project.org/wiki/documentation/self-service-password
function retrieve_ldap_password($login, $password){

    global $ldapUrl, $ldapBase, $ldapFilter, $ldapBindUser, $ldapBindPassword, $ldap_use_tls;

    # Connect to LDAP
    $ldap = @ldap_connect($ldapUrl);
    @ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    @ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

	if ($ldap_use_tls) {
		if(!@ldap_start_tls($ldap)){
			$result = "ldaperror";
			$err = "LDAP - failed to start tls";
			die("LDAP - failed to start tls");
		}    
	}

    # Bind
    $bind = @ldap_bind($ldap, $ldapBindUser, $ldapBindPassword);
    
    $errno = @ldap_errno($ldap);
    if ( $errno ) {
        $result = "ldaperror";
        $err = "LDAP - Bind error $errno  (".@ldap_error($ldap).")";
        die("LDAP - Bind error $errno  (".@ldap_error($ldap).")");
    }
    else {
		# Search for user
		$ldapFilter = str_replace("{login}", $login, $ldapFilter);
		$search = @ldap_search($ldap, $ldapBase, $ldapFilter);

		$errno = @ldap_errno($ldap);
		if ( $errno ) {
			$result = "ldaperror";
			$err = "LDAP - Search error $errno  (".@ldap_error($ldap).")";
		}
		else {
			# Get user DN
			$entry = @ldap_first_entry($ldap, $search);
			$userdn = @ldap_get_dn($ldap, $entry);

			if( !$userdn ) {
				$result = "badcredentials";
				$err = "LDAP - User $login not found";
			}
			else {    
				# Bind with password
				$bind = @ldap_bind($ldap, $userdn, $password);
				$errno = @ldap_errno($ldap);
				if ( $errno ) {
					$result = "badcredentials";
					$err = "LDAP - Bind user error $errno  (".@ldap_error($ldap).")";
				} else {
					// Everything is OK ;)
					$result = "OK";
					$err = "";
				}
			}
		}
	}
	@ldap_close($ldap);
	if ($result == "OK") {
		return True;
	}
	else {
		return False;
	}		
}

// deconnexion
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
	
	// Audit
	if (CONFIG_SOPLANNING_OPTION_AUDIT == 1 && CONFIG_SOPLANNING_OPTION_AUDIT_CONNEXIONS == 1)
	{
		logAction('D');
	}
	//Cross Application Laravel - SOPlanner
	setcookie("eleCaAuth", null, time()-24*3600, '/');
	unset($_SESSION['user_id']);
	session_regenerate_id();
	//Destroy Session
	@session_destroy();
	if(CONFIG_LOGOUT_REDIRECT != '') {
		header('Location: ' . CONFIG_LOGOUT_REDIRECT);
		exit;
	} else {
		header('Location: ../index.php' . (isset($_GET['language']) ? '?language=' . $_GET['language'] : ''));
		exit;
	}
}

function active_directory_login($username, $password){
	global $ADServer, $ADDomain;
	$ldap = ldap_connect($ADServer);
	if($ldap === false) {
		die('LDAP connexion failed');
	}
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

	$ldaprdn = $username . "@" . $ADDomain;
	$bind = @ldap_bind($ldap, $ldaprdn, $password);
	
	if(!$bind) {
		$ldaprdn = $ADDomain . "\\" . $username;
		$bind = @ldap_bind($ldap, $ldaprdn, $password);
	}

	if ($bind) {
		return true;
	} else {
		return false;
	}
}

if(isset($_GET['direct_periode_id']) && $_GET['direct_periode_id'] > 0) {
	// direct access from email to a specific task
	if(!isset($_GET['date']) || $_GET['date'] < date('Y-m-d')) {
		$_SESSION['message'] = 'Invalid URL';
		header('Location: index.php');
		exit;
	}
	if(!isset($_GET['hash']) || $_GET['hash'] != md5($_GET['direct_periode_id'] . '!¤' . $_GET['date'] . '¤' . CONFIG_SECURE_KEY)) {
		$_SESSION['message'] = 'Invalid URL';
		header('Location: index.php');
		exit;
	}
	
	$_SESSION['direct_periode_id'] = round($_GET['direct_periode_id']);
	header('Location: ../index.php');
	exit;
}

//login 
if(!isset($_POST['login']) || !isset($_POST['password']) || $_POST['login'] == '' || $_POST['password'] == '') {
	$_SESSION['message'] = 'erreur_bad_login';
	header('Location: ../index.php');
	exit;
}

// ldap password
if($ldapLogin) {
    if(!isset($_POST['password']) || !retrieve_ldap_password($_POST['login'], $_POST['password'])) {
        $_SESSION['message'] = 'erreur_bad_login';
        header('Location: ../index.php');
        exit;
    }
}

$user = New User();
 // AD account
if($ADLogin && ($_POST['login'] != 'admin')) {
	if(!active_directory_login($_POST['login'], $_POST['password'])){
		$_SESSION['message'] = 'erreur_bad_login';
		header('Location: ../index.php');
		exit;
	}
    if(!$user->db_load(array('login', '=', $_POST['login']))) {
        $_SESSION['message'] = 'erreur_bad_login';
		header('Location: ../index.php');
        exit;
    }
} elseif($ldapLogin && ($_POST['login'] != 'admin')) {
    if(!$user->db_load(array('login', '=', $_POST['login']))) {
        $_SESSION['message'] = 'erreur_bad_login';
        header('Location: ../index.php');
        exit;
    }
} else {
	$pwd = sha1("¤" . $_POST['password'] . "¤");
	if(!$user->db_load(array('login', '=', $_POST['login'], 'password', '=', $pwd))) {
        $_SESSION['message'] = 'erreur_bad_login';
		header('Location: ../index.php');
        exit;
    }
}

if($user->login_actif == 'non'){
	$_SESSION['message'] = 'erreur_bad_login';
	header('Location: ../index.php');
	exit;	
}

$_SESSION['user_id'] = $user->user_id;
//Cross Application Laravel - SOPlanner
setcookie("eleCaAuth", $user->user_id, time()+24*3600, '/');

$user->date_dernier_login = date('Y-m-d H:i:s');
$user->db_save();

// Recherche des préférences utilisateurs pour affichage du planning par défaut
$_SESSION['preferences'] = $user->getPreferences();
if($_SESSION['preferences']['vueDefaut'] == 'vueProjet'){
	$_SESSION['baseLigne'] = 'projets';
} else{
	$_SESSION['baseLigne'] = 'users';
}
setcookie('baseLigne', $_SESSION['baseLigne'], time()+60*60*24*500, '/');

if (!isset($_SESSION['preferences']['vueJourMois'])||($_SESSION['preferences']['vueJourMois']=='vueMois')) {
	$_SESSION['baseColonne'] = 'jours';	
}else
{
	$_SESSION['baseColonne'] = 'heures';
}
setcookie('baseColonne', $_SESSION['baseColonne'], time()+60*60*24*500, '/');
$_SESSION['dimensionCase'] = $_SESSION['preferences']['dimensionCase'];

// Préférence de sélection par défaut
$status = new GCollection('Status');
$status->db_load(array('defaut', '=', 1));
foreach ($status->getSmartyData() as $s)
{
	$_SESSION['status_par_defaut'][]=$s['status_id'];
}

if(count($_SESSION['status_par_defaut']) == 0){
	// si aucun statut par défaut, on prend le premier
	$status = new GCollection('Status');
	$status->db_load(array());
	$_SESSION['status_par_defaut'] = $status->get('status_id');
}

// Position du planning à l'ouverture (dans le cas de la date du jour, on force les cookies)
if($_SESSION['preferences']['positionPlanning'] == 'today'){
		$dateDebut = new Datetime();
		setcookie('date_debut', '', time()+60*60*24*500, '/');
		setcookie('date_debut_affiche', '', time()+60*60*24*500, '/');
		setcookie('date_debut_affiche_tache', '', time()+60*60*24*500, '/');
		
		if(isset($_COOKIE['date_fin_affiche'])) {
			$dateFin = clone $dateDebut;
			if ($_SESSION['baseColonne'] == 'heures')
			{
				$dateFin->modify('+' . CONFIG_DEFAULT_NB_DAYS_DISPLAYED . ' days');
				$_SESSION['date_fin_affiche'] = $dateFin->format(CONFIG_DATE_LONG);
			}else
			{
				$dateFin->modify('+' . CONFIG_DEFAULT_NB_MONTHS_DISPLAYED . ' months');
				$_SESSION['date_fin_affiche'] = $dateFin->format(CONFIG_DATE_LONG);			
			}
			setcookie('date_fin', $_SESSION['date_fin_affiche'], time()+60*60*24*500, '/');
			setcookie('date_fin_affiche', $_SESSION['date_fin_affiche'], time()+60*60*24*500, '/');
			setcookie('date_fin_affiche_tache', $_SESSION['date_fin_affiche'], time()+60*60*24*500, '/');
		}
}

// Audit
	if (CONFIG_SOPLANNING_OPTION_AUDIT == 1 && CONFIG_SOPLANNING_OPTION_AUDIT_CONNEXIONS == 1)
	{
		logAction('C');
	}
	
// Préférence de vue planning
if (isset($_SESSION['preferences']['vuePlanning']) && ($_SESSION['preferences']['vuePlanning']=="vueTaches") && (CONFIG_SOPLANNING_OPTION_TACHES == 1))
{
	header('Location: ../taches.php');
}else
{
	header('Location: ../planning.php');
}
exit;
?>