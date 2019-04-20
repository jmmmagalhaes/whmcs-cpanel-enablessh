<?php
//die("This feature is temporarily unavailable. Please check back within 24 hours.");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



use WHMCS\ClientArea;
use WHMCS\Database\Capsule;

define('CLIENTAREA', true);

require __DIR__ . '/init.php';

require_once("custom/cpanel.php");

error_reporting(0);
$ca = new ClientArea();



$ca->setPageTitle('Enable SSH Access');

$ca->addToBreadCrumb('index.php', Lang::trans('globalsystemname'));
$ca->addToBreadCrumb('enable-ssh.php', 'Enable SSH Access');

$ca->initPage();

$notifemail = "test@example.com"
$port = 22 // cPanel server SSH port

$ca->requireLogin(); 


// Check login status
if ($ca->isLoggedIn()) {

    $owner = Capsule::table('tblhosting')->where('id', '=', $id)->value("userid");

    if($owner == $ca->getUserID()) {
		// Product is owned by user

    	$status = Capsule::table('tblhosting')->where('id', '=', $id)->value("domainstatus");

    	if($status == "Active") {
    		// Status is active

			if(isset($_POST['submit'])) {
				
				$server = Capsule::table('tblhosting')->where('id', '=', $id)->value("server");

    				if($server == 4) {
    					$cpanelServer = "west04";
    				}else if($server == 6) {
    					$cpanelServer = "west05";
    				}else if($server == 7) {
    					$cpanelServer = "west06";
    				}else if($server == 8) {
    					$cpanelServer = "west07";
    				}else {
    					$cPanelserver = null;
    				}


    				if($cpanelServer != null) {
						
						$domain = Capsule::table('tblhosting')->where('id', '=', $id)->value("domain");
						$username = Capsule::table('tblhosting')->where('id', '=', $id)->value("username");
						
						// Type here
						
						$xmlapi = new xmlapi($servers[$cpanelServer]['ip']);
    					$xmlapi->password_auth("root", $servers[$cpanelServer]['password']);
    					$xmlapi->set_debug(1);
    					$xmlapi->set_output('json');
						
						$return = $xmlapi->modifyacct($username, array("HASSHELL"=>1));
						$info = json_decode($return, true);
						
						if($info["result"][0]["status"] == 1) {
							$ca->assign("output", "SSH access has been enabled.<br><br><b>Hostname:</b> us-" . $cpanelServer .".archhosting.net<br><b>Username:</b> " . $username . "<br><b>Password:</b> Identical to your cPanel password<br><b>SSH Port:</b> " . $port . "<br>");
							mail($notifemail, "SSH Enabled for " . $domain . "", "SSH access has been enabled for primary domain " . $domain . " with the username " . $username . " on server " . $cpanelServer . " by possible IP " . $_SERVER['REMOTE_ADDR'] . "");
						}else {	
							$ca->assign("output", "There was an error enabling SSH. Contact support.");
							mail($notifemail, "SSH Enable FAILED for " . $domain . "", "SSH access has NOT been enabled for primary domain " . $domain . " with the username " . $username . " on server " . $cpanelServer . " by possible IP " . $_SERVER['REMOTE_ADDR'] . " ERROR: " . $info["result"][0]["statusmsg"] ."");
						}
					}else {
						$ca->assign("output", "This tool only works on Web Hosting products. If your VPS is Linux, it already has SSH enabled!");
					}
				
				
				
			}
			
			$domain = Capsule::table('tblhosting')->where('id', '=', $id)->value("domain");
			$ca->assign("domain", $domain);

		}else {
    		// Product is not active
    		header("Location: clientarea.php");
    		exit();
    	}
		
	}else {
		// Product is not owned by user
    	header("Location: clientarea.php");
    	exit();
    }


} else {

	// User is not logged in

	header("Location: login.php");
	exit();
}


$ca->setTemplate('enablessh');

$ca->output();
