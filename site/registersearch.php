<?php
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

ob_start();

$params			= &JComponentHelper::getParams('com_opensim');
$debugsearch	= $params->get('jopensim_debug_search');


require_once('includes/opensim.class.php');

function debugzeile($zeile,$function = "") {
	if(!$function) $zeit = "\n\n########## ".date("d.m.Y H:i:s")." ##########\n";
	else $zeit = "\n\n########## ".date("d.m.Y H:i:s")." ##### ".$function." ##########\n";
	$logfile = "./interface.log";
	$handle = fopen($logfile,"a+");
	$logzeile = $zeit.$zeile."\n\n";
	fputs($handle,$logzeile);
	fclose($handle);
}

function GetURL($host, $port, $url) {
    $url = "http://$host:$port/$url";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $data = curl_exec($ch);
    if (!curl_errno($ch))
    {
        curl_close($ch);
        return $data;
    }
	return "";
}



// Get the request time as a timestamp for later
$timestamp = $_SERVER['REQUEST_TIME'];

$host		= JRequest::getVar('host');
$port		= JRequest::getVar('port');
$service	= JRequest::getVar('service');

// $debugzeile = var_export($_REQUEST,TRUE);
// debugzeile($debugzeile,"registersearch.php");

if ($host != "" && $port != "" && $service == "online") {
	if($debugsearch == "yes") {
		$zeile = "Registering host ".$host." with port ".$port." at timestamp ".$timestamp;
		debugzeile($zeile,"search register host online");
	}
	$query = sprintf("INSERT INTO #__opensim_search_hostsregister 
							(`host`,`port`,`register`) VALUES ('%1\$s','%2\$d','%3\$d')
						ON DUPLICATE KEY UPDATE
							`register` = '%3\$d'",
				$host,
				$port,
				$timestamp);

	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();

	$objDOM = new DOMDocument();
	$objDOM->resolveExternals = false;
	$objDOM->loadXML(GetURL($host, $port, "?method=collector"));
	$debug = var_export($objDOM,TRUE);
//	debugzeile($debug,"objDOM in registersearch.php");

} elseif ($host != "" && $port != "" && $service == "offline") {
	if($debugsearch == "yes") {
		$zeile = "Deregistering host ".$host." with port ".$port." at timestamp ".$timestamp;
		debugzeile($zeile,"search register host offline");
	}
	$query = sprintf("DELETE FROM #__opensim_search_hostsregister WHERE host = '%s' AND port = '%s'",$host,$port);
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
}

$output = ob_get_contents();
ob_end_clean();

if($debugsearch == "yes") {
	debugzeile($output,"reaction in registersearch.php");
}
?>