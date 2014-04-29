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

/*jimport('joomla.application.component.model');*/
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'opensim.php');

class opensimcpModelMaps extends OpenSimCpModelOpenSim {

	var $_data;
	var $_data_ext;
	var $_regiondata = null;
	var $_settingsData;
	var $filename = "maps.php";
	var $view = "maps";
	var $_os_db;
	var $_osgrid_db;
	var $_db;
	var $mapquery;

	/**
	 * Pagination object
	 * @var object
	 */
	var $_pagination = null;
	/**
	 * Items total
	 * @var integer
	 */
	var $_total = null;

	public function __construct() {
		global $mainframe, $option;
		parent::__construct();
		$this->getSettingsData();

		$filter = JRequest::getVar('search');
		$this->mapquery = $this->opensim->getAllRegionsQuery($filter);

		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int', FALSE);
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$this->_os_db =& $this->getOpenSimDB();
		$this->_osgrid_db =& $this->getOpenSimGridDB();
		$this->getMapData($filter,"regions.regionName","asc");
	}

	public function _buildQueryRegions($filter,$order,$direction) {
		$this->mapquery = $this->opensim->getAllRegionsQuery($filter,$order,$direction);
		return $this->mapquery;
	}

	public function getPagination() {
		if(!$this->_osgrid_db) return FALSE;
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	public function getTotal() {
		if(!$this->_osgrid_db) return FALSE;
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$this->_osgrid_db->setQuery($this->opensim->getAllRegionsQuery());
			$this->_osgrid_db->query();
			if($this->_osgrid_db->getErrorNum() > 0) {
				$errormsg = $this->_osgrid_db->getErrorNum().": ".stristr($this->_osgrid_db->getErrorMsg(),"sql=",TRUE)." in ".__FILE__." at line ".__LINE__;
				JFactory::getApplication()->enqueueMessage($errormsg,"error");
			}
			$this->_total = $this->_osgrid_db->getNumRows();
		}
		return $this->_total;
	}


	public function getMapData($filter,$order,$direction) {
		global $mainframe,$option;
		// Lets load the data if it doesn't already exist
		if (empty( $this->_settingsData )) $this->getSettingsData();
		if (!$this->_osgrid_db || JError::isError($this->_osgrid_db) || $this->_osgrid_db->getErrorNum() > 0) {
			return FALSE;
		}

		$lim   = $mainframe->getUserStateFromRequest("global.list.limit", 'limit', $mainframe->getCfg('list_limit'), 'int', FALSE);
		$lim0  = JRequest::getVar('limitstart', 0, '', 'int');

		$retval['settings'] = $this->_settingsData; // settings has only one line

		$opensim = $this->opensim;
		$query = $this->_buildQueryRegions($filter,$order,$direction);

		$this->_osgrid_db->setQuery($query,$lim0,$lim);
		$regiondata['regions'] = $this->_osgrid_db->loadAssocList();

		if(is_array($regiondata['regions'])) {
			foreach($regiondata['regions'] AS $key => $val) {
				$regiondata['regions'][$key]['posX'] = intval($regiondata['regions'][$key]['posX']);
				$regiondata['regions'][$key]['posY'] = intval($regiondata['regions'][$key]['posY']);
				$regiondata['regions'][$key]['maplink'] = str_replace("-","",$val['uuid']);
				$ownerdata = $opensim->getUserData($val['owner_uuid']);
				$regiondata['regions'][$key]['ownername'] = $ownerdata['firstname']." ".$ownerdata['lastname'];
				$mapinfo = $this->getMapInfo($val['uuid']);
				$regiondata['regions'][$key]['articleId'] = $mapinfo['articleId'];
				$regiondata['regions'][$key]['articleTitle'] = $this->getContentTitleFromId($mapinfo['articleId']);
				$regiondata['regions'][$key]['hidemap'] = $mapinfo['hidemap'];
			}
		}
		
		$this->_regiondata = $regiondata['regions'];
		$retval = array_merge($retval,$regiondata);
//		$firephp = FirePHP::getInstance(true);
//		$firephp->info($retval,"\$retval in ".__FILE__." bei Zeile ".__LINE__);
		return $retval;
	}

	public function getRegionName($maplink) {
		if(empty($this->_regiondata)) $this->getData();
		if(is_array($this->_regiondata)) {
			foreach($this->_regiondata AS $region) {
				if($region['maplink'] == $maplink) return $region['regionName'];
			}
			return "not found";
		} else {
			return FALSE;
		}
	}

	public function getArticle($regionUUID) { // diese Routine soll in einer nächsten Version einen Joomla Artikel den einzelnen Regionen zuordnen
		// TODO: checken ob $regionUUID in $this->regiondata vorhanden ist
		// andernfalls initialisierungsroutine anpassen
		// dann aus #__opensim_mapinfo lesen
		//
		// weitere noch notwendige Methoden:
		// setArticle($regionUUID)
		// deleteArticle($regionUUID)
	}

	public function getRegionUid($maplink) {
		if(empty($this->_regiondata)) $this->getData();
		if(is_array($this->_regiondata)) {
			foreach($this->_regiondata AS $region) {
				if($region['maplink'] == $maplink) return $region['uuid'];
			}
			return "not found";
		} else {
			return FALSE;
		}
	}

	public function getRegionAtLocation($locX,$locY) {
		if(!is_array($this->_regiondata)) return FALSE;
		foreach($this->_regiondata AS $region) {
			if($region['locX'] == $locX && $region['locY'] == $locY && $region['hidemap'] == 0) return $region;
		}
		return null;
	}

	public function getLocationRange() {
		if (!$this->_osgrid_db || JError::isError($this->_osgrid_db) || $this->_osgrid_db->getErrorNum() > 0) {
			return FALSE;
		}
		$opensim = $this->opensim;
		$rangequery = $opensim->getRegionRangeQuery();
		$this->_osgrid_db->setQuery($rangequery);
		$regionrange = $this->_osgrid_db->loadAssoc();
		return $regionrange;
	}

	public function savedefault() {
		$request = JRequest::get('request');
		
		$data['defaulthome'] = $request['region'];
		$data['mapstartX'] = $request['x'] / 2;
		$data['mapstartY'] = 256 - ($request['y'] / 2);
		$data['mapstartZ'] = 0;
		$this->saveConfig($data);
		return true;
	}

	public function savemanual() {
		$request = JRequest::get('request');
		$data['defaulthome'] = $request['region'];
		$data['mapstartX'] = $request['loc_x'];
		$data['mapstartY'] = $request['loc_y'];
		$data['mapstartZ'] = $request['loc_z'];
		$this->saveConfig($data);
		return true;
	}

	public function getMapInfo($regionUUID) {
		$retval = array();
		$query = sprintf("SELECT * FROM #__opensim_mapinfo WHERE regionUUID = '%s'",$regionUUID);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		if($db->getNumRows() == 1) {
			$retval = $db->loadAssoc();
		} else {
			$retval['regionUUID'] = $regionUUID;
			$retval['articleId'] = null;
			$retval['hidemap'] = 0;
			$retval['public'] = null;
		}
		return $retval;
	}

	public function setMapInfo($data) {
		$query = sprintf("INSERT INTO #__opensim_mapinfo (regionUUID,articleId,hidemap,`public`) VALUES ('%1\$s','%2\$d','%3\$d','%4\$d')
								ON DUPLICATE KEY UPDATE
							articleId = '%2\$d',
							`public` = '%4\$d',
							hidemap = '%3\$d'",
					$data['regionUUID'],
					$data['regionArticle'],
					$data['mapinvisible'],
					$data['mappublic']);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function removeMapArticle($regionUUID) {
		$query = sprintf("UPDATE #__opensim_mapinfo SET articleId = NULL WHERE regionUUID = '%s'",$regionUUID);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function setVisible($regionUUID,$status) {
		$query = sprintf("INSERT INTO #__opensim_mapinfo (regionUUID,articleId,hidemap) VALUES ('%1\$s',NULL,'%2\$d')
								ON DUPLICATE KEY UPDATE
							hidemap = '%2\$d'",
					$regionUUID,
					$status);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function updateMapconfig($data) {
		$savedata = array();
		if(array_key_exists("map_cache_age",$data))			$savedata['map_cache_age'] = $data['map_cache_age'];
		if(array_key_exists("mapcontainer_width",$data))	$savedata['mapcontainer_width'] = $data['mapcontainer_width'];
		if(array_key_exists("mapcontainer_height",$data))	$savedata['mapcontainer_height'] = $data['mapcontainer_height'];
		if(array_key_exists("mapcenter_offsetX",$data))		$savedata['mapcenter_offsetX'] = $data['mapcenter_offsetX'];
		if(array_key_exists("mapcenter_offsetY",$data))		$savedata['mapcenter_offsetY'] = $data['mapcenter_offsetY'];
		if(array_key_exists("map_defaultsize",$data))		$savedata['map_defaultsize'] = $data['map_defaultsize'];
		if(array_key_exists("map_minsize",$data))			$savedata['map_minsize'] = $data['map_minsize'];
		if(array_key_exists("map_maxsize",$data))			$savedata['map_maxsize'] = $data['map_maxsize'];
		if(array_key_exists("map_zoomstep",$data))			$savedata['map_zoomstep'] = $data['map_zoomstep'];
		if(count($savedata) == 9) { // all required values arrived
			$this->saveConfig($savedata);
			return TRUE;
		} else { // some error occured
			return FALSE;
		}
	}

	public function getArticles() {
		$retval = array();
		$query = "SELECT
						#__categories.title AS categorytitle,
						#__content.id AS articleid,
						#__content.title AS articletitle
					FROM
						#__categories,
						#__content
					WHERE
						#__categories.published = 1
					AND
						#__categories.access = 1
					AND
						#__categories.extension = 'com_content'
					AND
						#__content.state > 0
					AND
						#__categories.id = #__content.catid
					AND
						#__content.access = 1
					ORDER BY
						categorytitle,
						#__content.ordering";

		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();

//		$firephp = FirePHP::getInstance(true);
//		$firephp->info($db,"\$db in ".__FILE__." bei Zeile ".__LINE__);

		if($db->getNumRows() > 0) {
			$retval = $db->loadAssocList();
		}
		return $retval;
	}
}
?>
