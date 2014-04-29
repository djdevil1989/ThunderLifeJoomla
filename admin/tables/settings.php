<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component OpenSim
 * @copyright Copyright (C) 2010 FoTo50 http://www.foto50.com/opensim/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 *
 * Settings Table class
 *
 */
class TableSettings extends JTable {

	// easier use of joomla component
	var	$id							=	null; // * @var	int, Primary Key

	// imported from opensim webfrontent ... dunno yet if I really need them
	var	$userInventoryURI			=	null; // * @var	string
	var	$userAssetURI				=	null; // * @var	string
	var $allow_zoom					=	null; // * @var	int

	// this is the default region and the location x/y/z on that region for new registered user
	var $defaulthome				=	null; // * @var	string
	var $mapstartX					=	null; // * @var	int
	var $mapstartY					=	null; // * @var	int
	var $mapstartZ					=	null; // * @var	int

	// economy settings (however they ever will work ... ;)
	var $economy_sink_account		=	null; // * @var	string
	var $economy_source_account		=	null; // * @var	string

	// settings to connet to the opensim server(s) ### disabled - moved over to component parameters
//	var $gridserver_host			=	null; // * @var	string
//	var $gridserver_db				=	null; // * @var	string
//	var $gridserver_user			=	null; // * @var	string
//	var $gridserver_pwd				=	null; // * @var	string
//	var $regionserver_host			=	null; // * @var	string
//	var $regionserver_port			=	null; // * @var	int
//	var $regionserver_dbhost		=	null; // * @var	string
//	var $regionserver_dbport		=	null; // * @var	string
//	var $regionserver_db			=	null; // * @var	string
//	var $regionserver_user			=	null; // * @var	string
//	var $regionserver_pwd			=	null; // * @var	string

	// settings for the viewers login screen
	var $loginscreen_image						=	null; // * @var	string
	var $loginscreen_gridstatus					=	null; // * @var	int
	var $loginscreen_gridbox					=	null; // * @var	int
	var $loginscreen_xdays						=	null; // * @var	int
	var $loginscreen_boxes						=	null; // * @var	int
	var $loginscreen_msgbox_title				=	null; // * @var	string
	var $loginscreen_msgbox_title_background	=	null; // * @var	string
	var $loginscreen_msgbox_title_text			=	null; // * @var	string
	var $loginscreen_msgbox_message				=	null; // * @var	string
	var $loginscreen_color						=	null; // * @var	string
	var $loginscreen_msgbox_color				=	null; // * @var	string
	var $loginscreen_text_color					=	null; // * @var	string
	var $loginscreen_online_color				=	null; // * @var	string
	var $loginscreen_offline_color				=	null; // * @var	string
	var $loginscreen_msgbox_border				=	null; // * @var string

	// settings for remoteadmin ### disabled - moved over to component parameters
//	var $remoteadmin_enabled		=	null; // 0=disabled, 1=enabled
//	var $remoteadmin_url			=	null; // * @var	string
//	var $remoteadmin_port			=	null; // * @var	int
//	var $remoteadmin_password		=	null; // * @var	string

	// some permission settings for the joomla accounts
	var $userchange					=	null; // * @var int (&1 = can change first name, &2 = can change last name, &4 = can change email address, &8 = can change password)

	// enable/disable addons
	var $addons						=	null; // * @var int (&1 = offline messages, &2 = profiles, &4 = groups, &8 = inworldIdent)
	var $terminalchannel			=	null; // * @var int
	var $identminutes				=	null; // * @var int

	// get "normal" joomla content to display after new registration
	var $welcomecontent				=	null; // * @var	int 

	// allow certain lastnames or deny lastnames if nessecary
	var $lastnametype				=	null; // * @var	int 
	var $lastnamelist				=	null; // * @var	string 

	// GridMap settings
	var $mapcontainer_width			=	null; // * @var int
	var $mapcontainer_height		=	null; // * @var int
	var $mapcenter_offsetX			=	null; // * @var dec(7,2)
	var $mapcenter_offsetY			=	null; // * @var dec(7,2)
	var $map_defaultsize			=	null; // * @var int
	var $map_minsize				=	null; // * @var int
	var $map_maxsize				=	null; // * @var int
	var $map_zoomstep				=	null; // * @var int

	/**
	 * Constructor
	 *
	 * @param object Database connector	object
	 */
	function __construct( &$db ) {
		parent::__construct('#__opensim_settings',	'id', $db);
	}
}
