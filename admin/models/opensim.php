<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');
//jimport('joomla.application.component.model');

class OpenSimCpModelOpenSim extends JModelList {
	public $opensim;
	public $params;
	public $_os_db;
	public $_osgrid_db;
	public $os_user;
	public $moneyEnabled		= FALSE;
	public $userquery;
	public $_settingsData		= array();
	public $_moneySettingsData	= array();
	public $_total				= 0;
	public $_limit				= 20;
	public $_limitstart 		= 0;

	public function __construct($config = array()) {
		parent::__construct($config);
//		$this->_settingsData['debug'] = "";
		$params			= &JComponentHelper::getParams('com_opensim');
		$this->params	= $params;
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
		$this->getSettingsData();
		$this->opensim		= new opensim($osdbhost,$osdbuser,$osdbpasswd,$osdbname,$osdbport,$osgriddbhost,$osgriddbuser,$osgriddbpasswd,$osgriddbname,$osgriddbport);
		$this->userquery	= $this->opensim->getUserQuery(null);
		$this->_osgrid_db	= $this->getOpenSimGridDB();
		$this->_os_db		= $this->getOpenSimDB();

		if(!array_key_exists("addons",$this->_settingsData) || ($this->_settingsData['addons'] & 32) != 32) {
			$this->moneyEnabled = FALSE;
		} else {
			$this->moneyEnabled = $this->opensim->moneyEnabled();
		}
	}

	public function _buildSettingsQuery() {
		$query = "SELECT #__opensim_config.* FROM #__opensim_config";
		return $query;
	}

	public function getSettingsData() {
		// Lets load the data if it doesn't already exist
		if (empty( $this->_settingsData )) {
			$settings = array();
			$db =& JFactory::getDBO();
			$query = $this->_buildSettingsQuery();
			$db->setQuery($query);
			$jOpenSimSettings = $db->loadAssocList();
			if(is_array($jOpenSimSettings) && count($jOpenSimSettings) > 0) {
				foreach($jOpenSimSettings AS $jOpenSimSetting) {
					if($jOpenSimSetting['configname'] == "lastnamelist") {
						$lastnames = explode("\n",$jOpenSimSetting['configvalue']);
						if(is_array($lastnames) && count($lastnames) > 0) {
							foreach($lastnames AS $lastname) {
								if(trim($lastname)) $settings['lastnames'][] = trim($lastname);
							}
						}
						$settings['lastnamelist'] = $jOpenSimSetting['configvalue'];
						continue;
					}
					switch($jOpenSimSetting['configtype']) {
						case "int":
							$settings[$jOpenSimSetting['configname']] = intval($jOpenSimSetting['configvalue']);
						break;
						case "float":
							$settings[$jOpenSimSetting['configname']] = floatval($jOpenSimSetting['configvalue']);
						break;
						default:
							$settings[$jOpenSimSetting['configname']] = strval($jOpenSimSetting['configvalue']);
						break;
					}
				}
			} else {
				return FALSE;
			}

			$params							= &JComponentHelper::getParams('com_opensim');
			$this->params					= $params;
			$settings['oshost']				= $params->get('opensim_host');
			$settings['osport']				= $params->get('opensim_port');
			$settings['osdbhost']			= $params->get('opensim_dbhost');
			$settings['osdbuser']			= $params->get('opensim_dbuser');
			$settings['osdbpasswd']			= $params->get('opensim_dbpasswd');
			$settings['osdbname']			= $params->get('opensim_dbname');
			$settings['osdbport']			= $params->get('opensim_dbport');
			$settings['enableremoteadmin']	= $params->get('enableremoteadmin');
			$settings['remotehost']			= $params->get('remotehost');
			$settings['remoteport']			= $params->get('remoteport');
			$settings['remotepasswd']		= $params->get('remotepasswd');

			// ensure no empty value for required fiels (avoid PHP notices)
			$configvalues = $this->confignames();
			foreach($configvalues AS $configname => $configtype) {
				if(!array_key_exists($configname,$settings)) {
					switch($configtype) {
						case "int":
						case "float":
							$settings[$configname] = 0;
						break;
						case "char":
							$settings[$configname] = "";
						break;
						default:
							$settings[$configname] = null;
						break;
					}
				}
			}

			$this->_settingsData = $settings;
		}
		return $this->_settingsData;
	}

	public function saveConfig($data) {
		if(!is_array($data) || count($data) == 0) return FALSE;
		foreach($data AS $configname => $configvalue) {
			$this->saveConfigValue($configname,$configvalue);
		}
	}

	public function saveConfigValue($name,$value) {
		$configtype = $this->confignames($name);
		if($configtype === FALSE) return FALSE;
		switch($configtype) {
			case "int":
				$dbvalue = intval($value);
			break;
			case "float":
				$dbvalue = floatval($value);
			break;
			default:
				$dbvalue = strval(addslashes($value));
				$configtype = "char";
			break;
		}
		$query = sprintf("INSERT INTO #__opensim_config (#__opensim_config.configname,#__opensim_config.configtype,#__opensim_config.configvalue) VALUES ('%1\$s','%2\$s','%3\$s') ON DUPLICATE KEY UPDATE #__opensim_config.configvalue = '%3\$s'",
					$name,
					$configtype,
					$dbvalue);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function getOpenSimDB() {
		return $this->opensim->_os_db;
	}

	public function getOpenSimGridDB() {
		return $this->opensim->_osgrid_db;
	}

	public function checkLastnameAllowed($lastname) {
		switch($this->_settingsData['lastnametype']) {
			case 0:
				return TRUE;
			break;
			case -1:
				if(in_array($lastname,$this->_settingsData['lastnames'])) return FALSE;
				else return TRUE;
			break;
			case 1:
				if(in_array($lastname,$this->_settingsData['lastnames'])) return TRUE;
				else return FALSE;
			break;
		}
	}

	public function cleanIdents() { // removes old inworld idents if enabled
		$identminutes = $this->_settingsData['identminutes'];
		if($identminutes > 0) {
			$db =& JFactory::getDBO();
			$query = sprintf("DELETE FROM #__opensim_inworldident WHERE created < DATE_SUB(NOW(), INTERVAL %d MINUTE)",$identminutes);
			$db->setQuery($query);
			$db->query();
		}
	}

	public function getContentTitleFromId($id) {
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT title FROM #__content WHERE id = '%d'",$id);
		$db->setQuery($query);
		$contentTitle = $db->loadResult();
		if($contentTitle) return $contentTitle;
		else return JText::_('NONE');
	}

	public function getTerminalList($inactive = 0) {
		$db =& JFactory::getDBO();
		$query = "SELECT
					#__opensim_terminals.*,
					CONCAT('secondlife://',region,'/',location_x,'/',location_y,'/',location_z) AS surl
				FROM
					#__opensim_terminals";
		if(!$inactive) $query .= " WHERE active = '1'";
		$db->setQuery($query);
		$terminalList = $db->loadAssocList();
		return $terminalList;
	}

	public function opensimIsCreated() { // returns the opensim UUID if exists already for the user or FALSE if not
		$user =& JFactory::getUser();
		return $this->opensimRelation($user->id);
	}

	public function opensimRelation($uuid) {
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT opensimID FROM #__opensim_userrelation WHERE joomlaID = '%d'",$uuid);
		$db->setQuery($query);
		$uuid = $db->loadResult();
		if(!$uuid) return FALSE;
		else return $uuid;
	}

	public function opensimRelationReverse($uuid) {
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT joomlaID FROM #__opensim_userrelation WHERE opensimID = '%s'",$uuid);
		$db->setQuery($query);
		$uuid = $db->loadResult();
		if(!$uuid) return FALSE;
		else return $uuid;
	}

	public function opensimCreated($uuid) { // returns TRUE or FALSE if $uuid exists in the OpenSim database
		if(!$this->_osgrid_db) return FALSE;
		$opensim = $this->opensim;
		$query = $opensim->getUserDataQuery($uuid);
		$this->_osgrid_db->setQuery($query['userdata']);
		$this->_osgrid_db->query();
		$num_rows = $this->_osgrid_db->getNumRows();
		if($num_rows == 1) return TRUE;
		else return FALSE;
	}

	public function getUserData($userid) {
		if(!$this->_osgrid_db) return FALSE;
		$userdata = array();
		$griddata = array();
		$authdata = array();
		$opensim = $this->opensim;
		$query = $opensim->getUserDataQuery($userid);
		$this->_osgrid_db->setQuery($query['userdata']);
		$userdata = $this->_osgrid_db->loadAssoc();
		$this->_osgrid_db->setQuery($query['griddata']);
		$griddata = $this->_osgrid_db->loadAssoc();
		$this->_osgrid_db->setQuery($query['authdata']);
		$authdata = $this->_osgrid_db->loadAssoc();
		if(!is_array($griddata)) $griddata = $this->emptyGridData(); // in case no home region is defined and/or user never was online yet, give an empty array to prevent php warnings
		if(!is_array($userdata)) $userdata = $this->emptyUserData();
		if(!is_array($authdata)) $authdata = $this->emptyAuthData();
		$juserdata = $this->getJuserData($userid);
		$retval = array_merge($userdata,$griddata,$authdata,$juserdata);
//		$retval['query'] = $query;
//		if(!array_key_exists("im2email",$retval)) $retval['im2email'] = 0;
		return $retval;
	}

	public function emptyUserData() {
		$retval = array();
		$retval['uuid']			= null;
		$retval['firstname']	= null;
		$retval['lastname']		= null;
		$retval['name']			= null;
		$retval['email']		= null;
		$retval['userlevel']	= null;
		$retval['userflags']	= null;
		$retval['usertitle']	= null;
		$retval['born']			= null;
		return $retval;
	}

	public function emptyGridData() {
		$retval = array();
		$retval['last_login']	= null;
		$retval['last_logout']	= null;
		return $retval;
	}

	public function emptyAuthData() {
		$retval = array();
		$retval['passwordSalt']	= null;
		return $retval;
	}

	public function getUserDataList() {
//		global $mainframe,$option;
		$app =& JFactory::getApplication();
		if(!$this->_osgrid_db) return FALSE;
		$opensim = $this->opensim;
		if(empty($this->os_user)) {
			$lim				= $app->getUserStateFromRequest("global.list.limit", 'limit', $app->getCfg('list_limit'), 'int', FALSE);
			$lim0				= JRequest::getVar('limitstart', 0, '', 'int');
			$filter_order		= JRequest::getVar('filter_order',"created");
			$filter_order_Dir	= JRequest::getVar('filter_order_Dir',"DESC");
			$userquery = $this->userquery." ORDER BY ".$filter_order." ".$filter_order_Dir;
			$this->_osgrid_db->setQuery($userquery,$lim0,$lim);
//			$test = $this->getListQuery($this->_osgrid_db);
//			$test2 = $this->_osgrid_db->getQuery();
//			echo "<pre>----------#\n\n".$userquery."\n\n#------</pre>\n";
			$this->os_user = $this->_osgrid_db->loadAssocList();
			if($this->_osgrid_db->getErrorNum() > 0) {
				$errormsg = $this->_osgrid_db->getErrorNum().": ".stristr($this->_osgrid_db->getErrorMsg(),"sql=",TRUE)." in ".__FILE__." at line ".__LINE__;
				JFactory::getApplication()->enqueueMessage($errormsg." (".$this->userquery.")","error");
			} else {
				foreach($this->os_user AS $userkey => $user) {
					$statusquery = $opensim->userGridStatusQuery($user['userid']);
					$debug[] = $statusquery;
					$this->_osgrid_db->setQuery($statusquery);
					$userstatus = $this->_osgrid_db->loadAssoc();
					if(!is_array($userstatus)) {
						$userstatus['last_login'] = "";
						$userstatus['last_logout'] = "";
					}
					$userstatus['online'] = $opensim->getUserPresence($user['userid']);
					$this->os_user[$userkey] = array_merge($this->os_user[$userkey],$userstatus);
				}
			}
		}
//		$this->os_user['debug'] = $this->userquery;
		return $this->os_user;
	}

	public function updateOsPwd($newpassword,$osid) {
		if(empty($this->_osgrid_db)) $this->getOpenSimDB();
		if(!$this->_osgrid_db) return FALSE;
		$opensim = $this->opensim;
		$update = $opensim->getOsTableField('passwordHash');
		$osdata = $this->getUserData($osid);
		$update['fieldvalue'] = md5(md5($newpassword).":".$osdata['passwordSalt']);
		$update['osid'] = $osid;
		$this->updateValues($update);
	}

	public function updateOsField($fieldname,$fieldvalue,$osid) {
		if(empty($this->_osgrid_db)) $this->getOpenSimDB();
		if(!$this->_osgrid_db) return FALSE;
		$opensim = $this->opensim;
		$update = $opensim->getOsTableField($fieldname);
		$update['fieldvalue'] = $fieldvalue;
		$update['osid'] = $osid;
		$this->updateValues($update);
	}

	public function updateValues($data) {
		if(empty($this->_osgrid_db)) $this->getOpenSimDB();
		if(!$this->_osgrid_db) return FALSE;
		$query = sprintf("UPDATE %s SET %s = '%s' WHERE %s = '%s'",
							$data['table'],
							$data['field'],
							$data['fieldvalue'],
							$data['userid'],
							$data['osid']);
		$this->_osgrid_db->setQuery($query);
		$debug[] = $query;
		$result = $this->_osgrid_db->query();
		if($data['field'] == "passwordHash") return $query;
		else return $result;
	}

	public function getJuserData($uuid) { // Collect settings from Joomlas DB
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT im2email,visible,timezone FROM  #__opensim_usersettings WHERE `uuid` = '%s'",$uuid);
		$db->setQuery($query);
		$db->query();
		if($db->getNumRows() == 1) {
			$jUserData = $db->loadAssoc();
		} else {
			$jUserData = array( 'im2email'	=> 0,
								'visible'	=> 0,
								'timezone'	=> "");
		}
		return $jUserData;
	}

	public function getUserFriends($userid) {
		if(empty($this->_os_db)) $this->getOpenSimDB();
		if(!$this->_os_db) return FALSE;
		$opensim = $this->opensim;
		$query = $opensim->getUserDataQuery($userid);
		$this->_os_db->setQuery($query['friends']);
		$friends = $this->_os_db->loadAssocList();
		return $friends;
	}

	public function float_safe($value) {
	    $larr = localeconv();
	    $search = array(
	        $larr['decimal_point'],
	        $larr['mon_decimal_point'],
	        $larr['thousands_sep'],
	        $larr['mon_thousands_sep'],
	        $larr['currency_symbol'],
	        $larr['int_curr_symbol']
	    );
	    $replace = array('.', '.', '', '', '', '');

	    return str_replace($search, $replace, $value);
	}

	public function getEventCategory($cat = null) {
		$category = array(	'27' => JText::_('JOPENSIM_EVENTCATEGORY_ART'),
							'28' => JText::_('JOPENSIM_EVENTCATEGORY_CHARITY'),
							'22' => JText::_('JOPENSIM_EVENTCATEGORY_COMMERCIAL'),
							'18' => JText::_('JOPENSIM_EVENTCATEGORY_DISCUSSION'),
							'26' => JText::_('JOPENSIM_EVENTCATEGORY_EDUCATION'),
							'24' => JText::_('JOPENSIM_EVENTCATEGORY_GAMES'),
							'20' => JText::_('JOPENSIM_EVENTCATEGORY_MUSIC'),
							'29' => JText::_('JOPENSIM_EVENTCATEGORY_MISC'),
							'23' => JText::_('JOPENSIM_EVENTCATEGORY_NIGHTLIFE'),
							'25' => JText::_('JOPENSIM_EVENTCATEGORY_PAGEANT'),
							'19' => JText::_('JOPENSIM_EVENTCATEGORY_SPORT')
						);
		if(!$cat) return $category;
		if(array_key_exists($cat,$category)) {
			return $category[$cat];
		} else {
			return FALSE;
		}
	}

	public function confignames($name = null) {
		$confignames = array(
		"allow_zoom"							=> 'int',
		"defaulthome"							=> 'char',
		"mapstartX"								=> 'int',
		"mapstartY"								=> 'int',
		"mapstartZ"								=> 'int',
		"loginscreen_image"						=> 'char',
		"loginscreen_gridstatus"				=> 'int',
		"loginscreen_gridbox"					=> 'int',
		"loginscreen_xdays"						=> 'int',
		"loginscreen_boxes"						=> 'int',
		"loginscreen_msgbox_title"				=> 'char',
		"loginscreen_msgbox_title_background"	=> 'char',
		"loginscreen_msgbox_title_text"			=> 'char',
		"loginscreen_msgbox_message"			=> 'char',
		"loginscreen_color"						=> 'char',
		"loginscreen_msgbox_color"				=> 'char',
		"loginscreen_text_color"				=> 'char',
		"loginscreen_online_color"				=> 'char',
		"loginscreen_offline_color"				=> 'char',
		"loginscreen_msgbox_border"				=> 'char',
		"userchange"							=> 'int',
		"addons"								=> 'int',
		"terminalchannel"						=> 'int',
		"identminutes"							=> 'int',
		"welcomecontent"						=> 'int',
		"lastnametype"							=> 'int',
		"lastnamelist"							=> 'char',
		"map_cache_age"							=> 'int',
		"mapcontainer_width"					=> 'int',
		"mapcontainer_height"					=> 'int',
		"mapcenter_offsetX"						=> 'float',
		"mapcenter_offsetY"						=> 'float',
		"map_defaultsize"						=> 'int',
		"map_minsize"							=> 'int',
		"map_maxsize"							=> 'int',
		"map_zoomstep"							=> 'int',
		"eventtimedefault"						=> 'char',
		"listmatureevents"						=> 'char',
		"hiddenregions"							=> 'int'
		);
		if(!$name) return $confignames;
		else {
			if(array_key_exists($name,$confignames)) {
				return $confignames[$name];
			} else {
				return FALSE;
			}
		}
	}

	function getRegionDetails($uuid) {
		if(empty($this->_regiondata)) $this->getData();
		if(is_array($this->_regiondata)) {
			foreach($this->_regiondata AS $region) {
				if($region['uuid'] == $uuid) return $region;
			}
			return array("not found",$this->_regiondata);
		} else {
			return FALSE;
		}
	}

	public function createImageFolder() {
		$imagepath = JPATH_SITE.DS.'images';
		if(!is_dir($imagepath) || !is_writeable($imagepath)) return FALSE;
		$jopensimpath = $imagepath.DS.'jopensim';
		if(!is_dir($jopensimpath)) mkdir($jopensimpath);
		$regionpath = $jopensimpath.DS.'regions';
		if(!is_dir($regionpath)) mkdir($regionpath);
		return TRUE;
	}

	public function checkCacheFolder() {
		$cachefolder = JPATH_SITE.DS.'images'.DS.'jopensim'.DS.'regions';
		$retval['path'] = $cachefolder;
		if(is_dir($cachefolder)) {
			$retval['existing'] = TRUE;
			if(is_writable($cachefolder)) {
				$retval['writeable'] = TRUE;
			} else {
				$retval['writeable'] = FALSE;
			}
		} else {
			$retval['existing'] = FALSE;
		}
		return $retval;
	}

	public function mapCacheRefresh($regionUID) {
		$refresh = $this->mapNeedsRefresh($regionUID);
		if($refresh === TRUE) $this->refreshMap($regionUID);
	}

	public function mapNeedsRefresh($regionUID) {
		$chachefolder = $this->checkCacheFolder();
		if($chachefolder['existing'] == FALSE || $chachefolder['writeable'] == FALSE) return FALSE;
		$regiondata = $this->getRegionDetails($regionUID);
		$regionimage = $chachefolder['path'].DS.$regiondata['uuid'].".jpg";
		if(!is_file($regionimage)) {
			return TRUE;
		} else {
			if($this->_settingsData['map_cache_age'] == 0) return FALSE;
			$regioninfofile = $chachefolder['path'].DS.$regiondata['uuid'].".txt";
//			$test['filectime'] = filectime($regionimage);
//			$test['filemtime'] = filemtime($regionimage);
//			$test['cachetime'] = time() - (60*$this->_settingsData['map_cache_age']);
//			$test['time'] = time();
//			$test['stat'] = stat($regionimage);
//			$fh = fopen($regioninfofile,"w");
//			fwrite($fh,var_export($test,TRUE));
//			fclose($fh);
			$cachetime = time() - (60*$this->_settingsData['map_cache_age']);
			if($cachetime > filemtime($regionimage)) return TRUE;
			else return FALSE;
		}
	}

	public function refreshMap($regionUID) {
		$chachefolder = $this->checkCacheFolder();
		if($chachefolder['existing'] == FALSE || $chachefolder['writeable'] == FALSE) return FALSE;
		$regiondata = $this->getRegionDetails($regionUID);
		$regionimage = $chachefolder['path'].DS.$regiondata['uuid'].".jpg";
		$os_regionimage = str_replace("-","",$regiondata['uuid']);
		$source = $regiondata['serverURI']."index.php?method=regionImage".$os_regionimage;

		$mapdata = $this->getMapContent($source);
		if(array_key_exists("error",$mapdata)) { // some error occurred, lets copy an error image for it
			$this->maperrorimage($regionimage,$mapdata['error']);
			return FALSE;
		} elseif(array_key_exists("file_content",$mapdata) && $mapdata['file_content']) {
			$fh = fopen($regionimage,"w");
			fwrite($fh,$mapdata['file_content']);
			fclose($fh);
			return TRUE;
		}
	}

	public function getMapContent($source) { // gets image data from external server
		// lets check, what possibilities to read outside files is present
		$curl = extension_loaded('curl');
		$fopen = ini_get('allow_url_fopen');
		$retval['file_content'] = "";

		if(!$curl && !$fopen) { // there is no way to read from outside :( at least display an error image
			$retval['error'] = "impossible reading";
		} elseif($fopen) {
			$fexists = $this->http_test_existance($source);
			if($fexists['status'] == 200) {
				$handle = @fopen($source,'r');
				if($handle) {
					while (!feof($handle)) {
						$retval['file_content'] .= fread($handle,1024);
					}
					fclose($handle);
				} else { // could not open the image with fopen - display error image
					$retval['error'] = "fopen error (unknown)";
				}
			} else {
				$retval['error'] = $source."\nfopen error (status: ".$fexists['status'].")";
			}
		} else {
			ob_start();
			$ch = curl_init($source);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			$response = curl_getinfo($ch);
			if($response['http_code'] == 200) {
				$retval['file_content'] = ob_get_contents();
				ob_end_clean();
			} else { // could not open the image with cURL - display error image
				ob_end_clean();
				$retval['error'] = "cURL error ".$response['http_code'];
			}
		}
		return $retval;
	}

	public function maperrorimage($filename,$errormessage = "") {
		if(!$errormessage) $errormessage = "unknown error";
		$assetinfo = pathinfo(JPATH_COMPONENT_ADMINISTRATOR);
		$noregionimage = "components/".$assetinfo['basename']."/assets/images/noregion.png";
		$img = imagecreatefrompng($noregionimage);
		$textcolor = ImageColorAllocate ($img, 255, 255, 0);
		ImageString($img,1,20,200, $errormessage, $textcolor);
		imagejpeg($img,$filename);
		imagedestroy($img);
	}


	// Many thanks to Alexander Brock through http://aktuell.de.selfhtml.org/artikel/php/existenz/ for this very useful function :)
	public function http_test_existance($url,$timeout = 10) {
		$timeout = (int)round($timeout/2+0.00000000001);
		$return = array();

		$inf = parse_url($url);

		if(!isset($inf['scheme']) or $inf['scheme'] !== 'http') return array('status' => -1);
		if(!isset($inf['host'])) return array('status' => -2);
		$host = $inf['host'];

		if(!isset($inf['path'])) return array('status' => -3);
		$path = $inf['path'];
		if(isset($inf['query'])) $path .= '?'.$inf['query'];

		if(isset($inf['port'])) $port = $inf['port'];
		else $port = 80;

		$pointer = fsockopen($host, $port, $errno, $errstr, $timeout);
		if(!$pointer) return array('status' => -4, 'errstr' => $errstr, 'errno' => $errno);
		socket_set_timeout($pointer, $timeout);

		$head =
		  'HEAD '.$path.' HTTP/1.1'."\r\n".
		  'Host: '.$host."\r\n";

		if(isset($inf['user']))
			$head .= 'Authorization: Basic '.
			base64_encode($inf['user'].':'.(isset($inf['pass']) ? $inf['pass'] : ''))."\r\n";
		if(func_num_args() > 2) {
			for($i = 2; $i < func_num_args(); $i++) {
				$arg = func_get_arg($i);
				if(
					strpos($arg, ':') !== false and
					strpos($arg, "\r") === false and
					strpos($arg, "\n") === false
				) {
					$head .= $arg."\r\n";
				}
			}
		}
		else $head .=
			'User-Agent: Selflinkchecker 1.0 ('.$_SERVER['PHP_SELF'].')'."\r\n";

		$head .=
			'Connection: close'."\r\n"."\r\n";

		fputs($pointer, $head);

		$response = '';

		$status = socket_get_status($pointer);
		while(!$status['timed_out'] && !$status['eof']) {
			$response .= fgets($pointer);
			$status = socket_get_status($pointer);
		}
		fclose($pointer);
		if($status['timed_out']) {
			return array('status' => -5, '_request' => $head);
		}

		$res = str_replace("\r\n", "\n", $response);
		$res = str_replace("\r", "\n", $res);
		$res = str_replace("\t", ' ', $res);

		$ares = explode("\n", $res);
		$first_line = explode(' ', array_shift($ares), 3);

		$return['status'] = trim($first_line[1]);
		$return['reason'] = trim($first_line[2]);

		foreach($ares as $line) {
			$temp = explode(':', $line, 2);
			if(isset($temp[0]) and isset($temp[1])) {
				$return[strtolower(trim($temp[0]))] = trim($temp[1]);
			}
		}
		$return['_response'] = $response;
		$return['_request'] = $head;

		return $return;
	}

	public function profile_wantmask() {
		$wantmask['build']		=   1;
		$wantmask['explore']	=   2;
		$wantmask['meet']		=   4;
		$wantmask['behired']	=  64;
		$wantmask['group']		=   8;
		$wantmask['buy']		=  16;
		$wantmask['sell']		=  32;
		$wantmask['hire']		= 128;
		return $wantmask;
	}

	public function profile_skilsmask() {
		return $this->profile_skillsmask(); // left here for compatibility of old modules
	}

	public function profile_skillsmask() {
		$skillsmask['textures']			=  1;
		$skillsmask['architecture']		=  2;
		$skillsmask['modeling']			=  8;
		$skillsmask['eventplanning']	=  4;
		$skillsmask['scripting']		= 16;
		$skillsmask['customcharacters']	= 32;
		return $skillsmask;
	}

	public function getprofile($userid) {
		if(!$this->_osgrid_db) return FALSE;
		$opensim = $this->opensim;
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT * FROM #__opensim_userprofile WHERE avatar_id = '%s'",$userid);
		$db->setQuery($query);
		$profile = $db->loadAssoc();
		if(count($profile) == 0) { // in case no profile stored yet, fill it with empty values to avoid php notices
			$profile['aboutText']		= "";
			$profile['partner']			= null;
			$profile['url']				= "";
			$profile['wantmask']		= 0;
			$profile['wanttext']		= "";
			$profile['skillsmask']		= 0;
			$profile['skillstext']		= "";
			$profile['languages']		= "";
			$profile['firstLifeText']	= "";
		}
		if($profile['partner']) {
			$partnernamequery = $opensim->getUserNameQuery($profile['partner']);
			$this->_osgrid_db->setQuery($partnernamequery);
			$partner = $this->_osgrid_db->loadAssoc();
			$profile['partnername'] = $partner['firstname']." ".$partner['lastname'];
		} else {
			$profile['partnername'] = null;
		}
		return $profile;
	}

	public function checkOsClient($uuid) {
		return $this->opensimCreated($uuid);
	}

	public function checkClient($uuid) {
		$query = sprintf("SELECT * FROM #__opensim_moneybalances WHERE `user`= '%s'",$uuid);
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();
		if($num_rows == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getMoneySettings() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_moneySettingsData)) {
			$settings = array();
			$db =& JFactory::getDBO();
			$query = $this->moneySettingsQuery();
			$db->setQuery($query);
			$jOpenSimMoneySettings = $db->loadAssocList();
			if(is_array($jOpenSimMoneySettings) && count($jOpenSimMoneySettings) > 0) {
				$confignames = $this->getMoneyConfigNames();
				foreach($jOpenSimMoneySettings AS $jOpenSimMoneySetting) {
					if(array_key_exists($jOpenSimMoneySetting['field'],$confignames)) {
						switch($confignames[$jOpenSimMoneySetting['field']]) {
							case "int":
								$settings[$jOpenSimMoneySetting['field']] = intval($jOpenSimMoneySetting['value']);
							break;
							case "float":
								$settings[$jOpenSimMoneySetting['field']] = floatval($jOpenSimMoneySetting['value']);
							break;
							default:
								$settings[$jOpenSimMoneySetting['field']] = strval($jOpenSimMoneySetting['value']);
							break;
						}
					}
				}
			}
			$this->_moneySettingsData = $settings;
		}
		return $this->_moneySettingsData;
	}

	public function getMoneyConfigNames($name = null) {
		$confignames = array(
			"name"				=> 'char',
			"bankerUID"			=> 'char',
			"bankerName"		=> 'char',
			"groupCharge"		=> 'int',
			"uploadCharge"		=> 'int',
			"groupMinDividend"	=> 'int',
			"startBalance"		=> 'int'
		);
		if(!$name) return $confignames;
		else {
			if(array_key_exists($name,$confignames)) {
				return $confignames[$name];
			} else {
				return FALSE;
			}
		}
	}

	public function moneySettingsQuery() {
		$query = "SELECT #__opensim_moneysettings.* FROM #__opensim_moneysettings";
		return $query;
	}

	public function setBalance($uuid,$amount) {
		$this->balanceExists($uuid); // $uuid could be a group, see if it exists and if not, create a balance line for it
		$query = sprintf("UPDATE #__opensim_moneybalances SET balance = balance + %d WHERE `user`= '%s'",$amount,$uuid);
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function getBalance($uuid) {
		$query = sprintf("SELECT #__opensim_moneybalances.balance FROM #__opensim_moneybalances WHERE `user`= '%s'",$uuid);
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();
		if($num_rows == 1) {
			return $db->loadResult();
		} else {
			return FALSE;
		}
	}

	public function getCurrencyName() {
		$query	= "SELECT value FROM #__opensim_moneysettings WHERE `field` = 'name'";
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$name = $db->loadResult();
		return $name;
	}

	public function balanceExists($uuid) { // if this $uuid does not exist yet, it will create a 0 Balance for it
		$query	= sprintf("SELECT balance FROM #__opensim_moneybalances WHERE `user` = '%s'",$uuid);
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();
		if($num_rows == 0) {
			$query = sprintf("INSERT INTO #__opensim_moneybalances (`user`,`balance`) VALUES ('%s',0)",$uuid);
			$db->setQuery($query);
			$db->query();
		}
	}

	public function TransferMoney($parameter) {
		$isSender	= $this->checkOsClient($parameter['senderID']);
		$isReceiver	= $this->checkOsClient($parameter['receiverID']);

		if($isSender === FALSE) {
			$retval['success']	= FALSE;
			$retval['message']	= "Could not locate senderID ".$parameter['senderID'];
		} elseif($isReceiver === FALSE) {
			$retval['success']	= FALSE;
			$retval['message']	= "Could not locate receiverID ".$parameter['receiverID'];
		} else {
			if(!$this->checkClient($parameter['senderID'])) $this->balanceExists($parameter['senderID']);
			if(!$this->checkClient($parameter['receiverID'])) $this->balanceExists($parameter['receiverID']);
			$parameter['time'] = time();
			$parameter['status'] = 0;
			$this->insertTransaction($parameter);

			$this->setBalance($parameter['receiverID'],$parameter['amount']);
			$this->setBalance($parameter['senderID'],-$parameter['amount']);

			$retval['success']				 = TRUE;
			$retval['clientUUID']			 = (isset($parameter['clientUUID']))			? $parameter['clientUUID']:null;
			$retval['clientSessionID']		 = (isset($parameter['clientSessionID']))		? $parameter['clientSessionID']:null;
			$retval['clientSecureSessionID'] = (isset($parameter['clientSecureSessionID']))	? $parameter['clientSecureSessionID']:null;;
		}
		return $retval;
	}

	public function insertTransaction($parameter) {

		$senderID 				= (isset($parameter['senderID']))				? $parameter['senderID']:"";
		$receiverID				= (isset($parameter['receiverID']))				? $parameter['receiverID']:"";
		$amount					= (isset($parameter['amount']))					? $parameter['amount']:0;
		$objectID				= (isset($parameter['objectID']))				? $parameter['objectID']:"";
		$regionHandle			= (isset($parameter['regionHandle']))			? $parameter['regionHandle']:"";
		$transactionType		= (isset($parameter['transactionType']))		? $parameter['transactionType']:"";
		$time					= (isset($parameter['time']))					? $parameter['time']:time();
		$senderSecureSessionID	= (isset($parameter['senderSecureSessionID']))	? $parameter['senderSecureSessionID']:"";
		$status					= (isset($parameter['status']))					? $parameter['status']:0;
		$description			= (isset($parameter['description']))			? $parameter['description']:"";

		$query = sprintf("INSERT INTO #__opensim_moneytransactions (`UUID`,sender,receiver,amount,objectUUID,regionHandle,type,`time`,`secure`,`status`,description)
															VALUES
																	(UUID(),'%s','%s','%d','%s','%s','%s','%d','%s','%d','%s')",
									$senderID,
									$receiverID,
									$amount,
									$objectID,
									$regionHandle,
									$transactionType,
									$time,
									$senderSecureSessionID,
									$status,
									$description);

		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function transactionlist($uuid,$days = 0) {
		if(!array_key_exists("bankerUID",$this->_moneySettingsData) || !$this->_moneySettingsData['bankerUID']) $this->getMoneySettings();
		$transactions = $this->getTransactions($uuid,$days);
		if(is_array($transactions) && count($transactions) > 0) {
			foreach($transactions AS $key => $transaction) {
				if($transaction['direction'] == "in") {
					$transactions[$key]['receivername'] = "";
					if($transaction['sender'] == $this->_moneySettingsData['bankerUID']) {
						$transactions[$key]['sendername'] =  ($this->_moneySettingsData['bankerName']) ? $this->_moneySettingsData['bankerName']:JText::_('JOPENSIM_MONEY_BANKERNAME');
					} else {
						$userdata = $this->getUserData($transaction['sender']);
						$transactions[$key]['sendername'] = $userdata['name'];
					}
				} else {
					if($transaction['receiver'] == $this->_moneySettingsData['bankerUID']) {
						$transactions[$key]['receivername'] =  ($this->_moneySettingsData['bankerName']) ? $this->_moneySettingsData['bankerName']:JText::_('JOPENSIM_MONEY_BANKERNAME');
					} else {
						$userdata = $this->getUserData($transaction['receiver']);
						$transactions[$key]['receivername'] = $userdata['name'];
					}
					$transactions[$key]['sendername'] = "";
				}
				$transactions[$key]['transactiontime'] = date(JText::_('JOPENSIM_MONEY_TIMEFORMAT'),$transaction['time']);
			}
		}
		return $transactions;
	}

	public function getTransactions($uuid = null,$days = 0) {
		$db		=& JFactory::getDBO();
		$query = $db->getQuery(true);
		if($uuid) $query->select('IF(#__opensim_moneytransactions.receiver = '.$db->quote($uuid).',"in","out") AS direction');
		else $query->select('"none" AS direction');
		$query->select('#__opensim_moneytransactions.*');
		$query->from('#__opensim_moneytransactions');
		if($uuid) {
			$query->where('(#__opensim_moneytransactions.sender = '.$db->quote($uuid).' OR #__opensim_moneytransactions.receiver = '.$db->quote($uuid).')');
		}
		if($days > 0) {
			if($days == 365) $query->where('#__opensim_moneytransactions.`time` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR))');
			else $query->where('#__opensim_moneytransactions.`time` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL '.(int)$days.' DAY))');
		}
		$query->order('#__opensim_moneytransactions.`time` DESC');
		$db->setQuery($query);
		$transactions = $db->loadAssocList();
//		$this->_total = count($transactions);
//		$db->setQuery($query,$this->_limitstart,$this->_limit);
		$db->setQuery($query,0,15);
		$transactions = $db->loadAssocList();
		return $transactions;
	}

	public function getTransactionNames($transactionlist,$uuid = null) {
		if(!is_array($transactionlist)) return FALSE;
		if(count($transactionlist) == 0) return array();
		$opensim = $this->opensim;
		if($uuid) {
			if($uuid == $this->_moneySettingsData['bankerUID']) {
				$name = ($this->_moneySettingsData['bankerName']) ? $this->_moneySettingsData['bankerName']:JText::_('JOPENSIM_MONEY_BANKERNAME');
			} else {
				$name = $opensim->getUserName($uuid,"full");
			}
		}
		foreach($transactionlist AS $key => $transaction) {
			if($uuid) {
				if($transaction['direction'] == "in") {
					$transactionlist[$key]['receivername']	= $name;
					$transactionlist[$key]['sendername']	= $opensim->getUserName($transaction['sender'],"full");
				} elseif($transaction['direction'] == "out") {
					$transactionlist[$key]['receivername']	= $opensim->getUserName($transaction['receiver'],"full");
					$transactionlist[$key]['sendername']	= $name;
				} else {
					$transactionlist[$key]['receivername']	= $opensim->getUserName($transaction['receiver'],"full");
					$transactionlist[$key]['sendername']	= $opensim->getUserName($transaction['sender'],"full");
				}
			} else {
				$transactionlist[$key]['receivername']	= $opensim->getUserName($transaction['receiver'],"full");
				$transactionlist[$key]['sendername']	= $opensim->getUserName($transaction['sender'],"full");
			}
			if($transaction['receiver'] == $this->_moneySettingsData['bankerUID']) $transactionlist[$key]['receivername'] = ($this->_moneySettingsData['bankerName']) ? $this->_moneySettingsData['bankerName']:JText::_('JOPENSIM_MONEY_BANKERNAME');
			if($transaction['sender'] == $this->_moneySettingsData['bankerUID']) $transactionlist[$key]['sendername'] = ($this->_moneySettingsData['bankerName']) ? $this->_moneySettingsData['bankerName']:JText::_('JOPENSIM_MONEY_BANKERNAME');
		}
		return $transactionlist;
	}

	public function getTransactionOpenSimNames($items) {
		if(!is_array($items)) return FALSE;
		if(count($items) == 0) return $items;
		if (empty($this->_moneySettingsData)) $this->getMoneySettings();
		$bankerUID = $this->_moneySettingsData['bankerUID'];
		$bankerName = $this->_moneySettingsData['bankerName'];
		foreach($items AS $key => $item) {
			$items[$key]->sendername = ($item->sender == $bankerUID) ? $bankerName:$this->getOpenSimName($item->sender);
			$items[$key]->receivername = ($item->receiver == $bankerUID) ? $bankerName:$this->getOpenSimName($item->receiver);
		}
		return $items;
	}

	public function getOpenSimName($uuid) {
		$name		= $this->opensim->getUserName($uuid,'full');
		return $name;
	}

	public function removehidden($regionarray) {
		foreach($regionarray AS $key => $region) {
			if($region['hidemap'] == 1) unset($regionarray[$key]);
		}
		return $regionarray;
	}
}
?>