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

class opensimModelMaps extends OpenSimCpModelOpenSim {

	var $_data;
	var $_data_ext;
	var $_regiondata = null;
	var $_settingsData;
	var $filename = "maps.php";
	var $view = "maps";
	var $_os_db;
	var $_osgrid_db;
	var $_db;

	function __construct() {
		parent::__construct();
		$this->getSettingsData();
		$this->_os_db = $this->getOpenSimDB();
		$this->_osgrid_db = $this->getOpenSimGridDB();
		$this->getData();
	}

	function _buildQueryRegions() {
		$opensim = $this->opensim;
		$query = $opensim->getAllRegionsQuery();
		return $query;
	}

	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty( $this->_settingsData )) $this->getSettingsData();
		if (!$this->_osgrid_db || JError::isError($this->_osgrid_db) || $this->_osgrid_db->getErrorNum() > 0) {
			return FALSE;
		}

		$retval['settings'] = $this->_settingsData; // settings has only one line

		$query = $this->_buildQueryRegions();

		$this->_osgrid_db->setQuery($query);
		$regiondata['regions'] = $this->_osgrid_db->loadAssocList();

		if(is_array($regiondata['regions'])) {
			foreach($regiondata['regions'] AS $key => $val) {
				$regiondata['regions'][$key]['maplink'] = str_replace("-","",$val['uuid']);
				$mapinfo = $this->getMapInfo($val['uuid']);
				$regiondata['regions'][$key]['articleId'] = $mapinfo['articleId'];
				$regiondata['regions'][$key]['hidemap'] = $mapinfo['hidemap'];
			}
		}
		
		$this->_regiondata = $regiondata['regions'];
		$retval = array_merge($retval,$regiondata);
		return $retval;
	}

	function getRegionName($maplink) {
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

	function getRegionUid($maplink) {
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

	function getRegionAtLocation($locX,$locY) {
		if(!is_array($this->_regiondata)) return FALSE;
		foreach($this->_regiondata AS $region) {
			if($region['locX'] == $locX && $region['locY'] == $locY && $region['hidemap'] == 0) return $region;
		}
		return null;
	}

	function getRegionsInRow($locY) {
		if(!is_array($this->_regiondata)) return FALSE;
		$counter = 0;
		foreach($this->_regiondata AS $region) {
			if($region['locY'] == $locY && $region['hidemap'] == 0) $counter++;
		}
		return $counter;
	}

	function getRegionsInColumn($locX) {
		if(!is_array($this->_regiondata)) return FALSE;
		$counter = 0;
		foreach($this->_regiondata AS $region) {
			if($region['locX'] == $locX && $region['hidemap'] == 0) $counter++;
		}
		return $counter;
	}

	function getLocationRange() {
		if (!$this->_osgrid_db || JError::isError($this->_osgrid_db) || $this->_osgrid_db->getErrorNum() > 0) {
			return FALSE;
		}
		$opensim = $this->opensim;
		$rangequery = $opensim->getRegionRangeQuery();
		$_SESSION['rangequery'] = $rangequery;
		$this->_osgrid_db->setQuery($rangequery);
		$regionrange = $this->_osgrid_db->loadAssoc();
		if(count($regionrange) > 0 && $regionrange['maxX'] && $regionrange['maxY'] && $regionrange['minX'] && $regionrange['minY']) return $regionrange;
		else return FALSE;
	}

	function getMapInfo($regionUUID) {
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
		}
		return $retval;
	}
}
?>
