<?php
error_reporting(0);
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim (Communication Interface with the OpenSim Server)
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
/* Initialize Joomla framework */
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname("../../index.php") );
define( 'DS', DIRECTORY_SEPARATOR );
/* Required Files */
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
/* To use Joomla's Database Class */
require_once ( JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
/* Create the Application */
$mainframe =& JFactory::getApplication('site');

/* Load the language file from the component opensim */

$lang =& JFactory::getLanguage();
$extension = 'com_opensim';
$base_dir = JPATH_SITE;

// $language_tag = 'en-GB';
// $lang->load($extension, $base_dir, $language_tag, true);
$lang->load($extension, $base_dir, null, true);

/**************************************************/
// My code starts here...
/**************************************************/
require_once('includes/opensim.class.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_opensim'.DS.'models'.DS.'opensim.php');
$opensim_model = new OpenSimCpModelOpenSim();


function getXMLrpcFunctions() {
	$retval['currency'] = array(
								"GetBalance",
								"TransferMoney",
								"AmountCovered",
								"ApplyCharge",
								"getSettingsData",
								"buy_land_prep",
								"buy_land",
								"getCurrencyQuote",
								"preflightBuyLandPrep",
								"buyLandPrep",
								"buyCurrency",
								"clientInfo"); // all methods for handling money
	return $retval;
}

function getOpenXMLrpcFunctions() {
	$retval = array(
								"getSettingsData",
								"buy_land_prep",
								"preflightBuyLandPrep",
								"buyLandPrep"); // all open methods (do not need remoteIP check)
	return $retval;
}

$remoteip = $_SERVER['REMOTE_ADDR']; // who is talking with us?

$input = file_get_contents("php://input");
$responsefunction = ""; // temporary debug switch to know, if groups or profile (soon search) triggered

function simpledebugzeile($zeile) {
	$zeit = date("Y-m-d H:i:s");
	$logfile = "./currency.log";
	$handle = fopen($logfile,"a+");
	$logzeile = $zeit."\t".$zeile."\n";
	fputs($handle,$logzeile);
	fclose($handle);
}

function debugzeile($zeile,$function = "") {
	if(!$function) $zeit = "\n\n########## ".date("d.m.Y H:i:s")." ##########\n";
	else $zeit = "\n\n########## ".date("d.m.Y H:i:s")." ##### ".$function." ##########\n";
	$zeile = var_export($zeile,TRUE);
	$logfile = "./currency.log";
	$handle = fopen($logfile,"a+");
	$logzeile = $zeit.$zeile."\n\n";
	fputs($handle,$logzeile);
	fclose($handle);
}

function mysqlsafestring($string) {
	return mysql_real_escape_string(stripslashes($string));
}

function jOpenSimSettings() {
	global $opensim_model;
	$settings = $opensim_model->getSettingsData();
	return $settings;
}

function addonSettings() {
	$settings				= jOpenSimSettings();
	$addon					= $settings['addons'];
	$retval['messages']		= $addon & 1;
	$retval['profile']		= $addon & 2;
	$retval['groups']		= $addon & 4;
	$retval['inworldident']	= $addon & 8;
	return $retval;
}


function roundoff($v, $d) {
	$r = pow(10, $d);
	$v *= $r;
	if ($v - floor($v) >= 0.5) {
		return(ceil($v)/$r);
	} else {
		return (floor($v)/$r);
	}
}

$params = &JComponentHelper::getParams('com_opensim');

$osdbhost		= $params->get('opensim_dbhost');
$osdbuser		= $params->get('opensim_dbuser');
$osdbpasswd		= $params->get('opensim_dbpasswd');
$osdbname		= $params->get('opensim_dbname');
$osdbport		= $params->get('opensim_dbport');
$osgriddbhost	= $params->get('opensimgrid_dbhost');
$osgriddbuser	= $params->get('opensimgrid_dbuser');
$osgriddbpasswd	= $params->get('opensimgrid_dbpasswd');
$osgriddbname	= $params->get('opensimgrid_dbname');
$osgriddbport	= $params->get('opensimgrid_dbport');
$opensim = new opensim($osdbhost,$osdbuser,$osdbpasswd,$osdbname,$osdbport,$osgriddbhost,$osgriddbuser,$osgriddbpasswd,$osgriddbname,$osgriddbport);

$debug['access']	= $params->get('jopensim_debug_access');
$debug['input']		= $params->get('jopensim_debug_input');
$debug['other']		= $params->get('jopensim_debug_other');
$debug['currency']	= $params->get('jopensim_debug_currency');

if($debug['currency'] == "yes") define("_JOPENSIMMONEYDEBUG",TRUE);
else define("_JOPENSIMMONEYDEBUG",FALSE);

$grp_readkey	= $params->get('grp_readkey');
$grp_writekey	= $params->get('grp_writekey');

$addons = addonSettings();

if($addons['messages'] > 0) $addon_offlinemsg		=  1;
else  $addon_offlinemsg		=  0;
if($addons['profile'] > 0) $addon_profiles			=  1;
else $addon_profiles		=  0;
if($addons['groups'] > 0) $addon_groups				=  1;
else $addon_groups			=  0;
if($addons['inworldident'] > 0) $addon_inworldident	=  1;
else $addon_inworldident	=  0;

$response2opensim = FALSE;

ob_start();

// some debug to test directly:
if(isset($_REQUEST['test'])) {
    require_once("xmlrpc/xmlrpc.inc"); // get the xmlrpc library from FlotSam
    require_once("xmlrpc/xmlrpcs.inc");
    if(isset($_REQUEST['test'])) {
		switch($_REQUEST['test']) {
			case "test":
				echo "test!";
				exit;
			break;
			default:
				$test = $_REQUEST['test'];
				$retval = null;
				$xmlrpcfunctions	= getXMLrpcFunctions();
				$currencyfunctions	= $xmlrpcfunctions['currency'];

			    require_once("xmlrpc/xmlrpc.inc"); // get the xmlrpc library from FlotSam
			    require_once("xmlrpc/xmlrpcs.inc");

				if(in_array($test,$currencyfunctions)) {
					$response2opensim = TRUE;
					include('includes/functions_currency.php'); // get the functions for profiles
					$params = $_REQUEST;
					unset($params['test']);
					$retval = call_user_func($test,$params);
				}
				if($retval) debugprint($retval,"retval");
				else echo "no response for ".$test." :(<br />";
				exit;
			break;
		}
	}
}

if($debug['access']	== "yes") simpledebugzeile("Request coming from ".$remoteip);
if($debug['input']	== "yes") debugzeile($input,"input");

$method = $opensim->parseOSxml($input,"method"); // get name of method
$opencurrencyfunctions = getOpenXMLrpcFunctions();
if(in_array($method,$opencurrencyfunctions)) {
	$currencyaccess = TRUE;
} else {
	$currencyaccess = $opensim->checkRegionIP($remoteip);
}

if($currencyaccess === TRUE) { // only registered regions (or better their server) should access this
	
	if($debug['access']	== "yes") simpledebugzeile("Access granted to ".$remoteip." for ".$method." at line ".__LINE__." in ".__FILE__);
	if(!$method) {
		exit; // no method? nothing we can do then ... :(
	}

	$xmlrpcfunctions	= getXMLrpcFunctions();
	$currencyfunctions	= $xmlrpcfunctions['currency'];

    require_once("xmlrpc/xmlrpc.inc"); // get the xmlrpc library from FlotSam
    require_once("xmlrpc/xmlrpcs.inc");

	if(in_array($method,$currencyfunctions)) {
		$response2opensim = TRUE;
		$responsefunction = "currency";
		include('includes/functions_currency.php'); // get the functions for profiles
	} else {
		if($debug['other']	== "yes") debugzeile("\nmethode nicht gefunden:\n\n".var_export($method,TRUE));
		exit;
	}

	$xmlrpcserver->functions_parameters_type = 'phpvals';
	if($response2opensim === TRUE) {
		$response = $xmlrpcserver->service(null,TRUE);
	} else {
		// can somebody tell me how to return a "fake" response without f*** up the login after disabling groups?
		// $response = $xmlrpcserver->service("addon_disabled",TRUE); <--- that didnt work with groups disabled
		// see interface.php for detailed info
		$response = $xmlrpcserver->service(null,TRUE);
	}
	if(isset($groupDBCon) && is_resource($groupDBCon)) mysql_close($groupDBCon);
	echo $response;
} else {
	//	no access? Just dont answer at all ;)
	if($debug['access']	== "yes") simpledebugzeile("No access for ".$remoteip." at line ".__LINE__." in ".__FILE__);
}

$output = ob_get_contents();
ob_end_clean();

$response = var_export($output,TRUE);
//$response .= "\n\n########## debug ##########\n".var_export($debug,TRUE)."\n";
if($debug['currency'] == "yes") debugzeile("\nresponse for ".$method.":\n\n".$response,"response for currency");

echo trim($output);
?>