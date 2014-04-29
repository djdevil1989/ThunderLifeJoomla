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

// require_once(JPATH_ROOT.DS.'administrator/firephp/FirePHP.class.php');

class opensimModelinworldsearch extends OpenSimCpModelOpenSim {

	var $_settingsData;
	var $filename = "inworldsearch.php";
	var $view = "inworld";
	var $_os_db = null;
	var $_osgrid_db = null;

	public function __construct() {
		parent::__construct();
		$this->getSettingsData();
		$this->getOpenSimDB();
		$this->_os_db =& $this->getOpenSimDB();
		$this->_osgrid_db =& $this->getOpenSimGridDB();
	}

	public function getSearchOptions($enabled = TRUE) { // returns array with "enabled == false --> all" or "enabled == true --> only enabled" search options
		$db =& JFactory::getDBO();
		if($enabled === TRUE) $where = "WHERE enabled = '1'";
		else $where = "";
		$query = sprintf("SELECT * FROM #__opensim_search_options %s ORDER BY reihe",$where); 
		$db->setQuery($query);
		$searchoptions = $db->loadAssocList();
		return $searchoptions;
	}

	public function searchAll($searchterm) {
		$searchoptions = $this->getSearchOptions();
		if(is_array($searchoptions) && count($searchoptions) > 0) {
			$retval = array();
			foreach($searchoptions AS $option) {
				switch($option['searchoption']) {
					case "JOPENSIM_SEARCH_OBJECTS":
						$retval['JOPENSIM_SEARCH_OBJECTS'] = $this->searchobjects($searchterm);
					break;
					case "JOPENSIM_SEARCH_PARCELS":
						$retval['JOPENSIM_SEARCH_PARCELS'] = $this->searchparcels($searchterm);
					break;
					case "JOPENSIM_SEARCH_PARCELSALES":
						$retval['JOPENSIM_SEARCH_PARCELSALES'] = $this->searchparcelsale($searchterm);
					break;
					case "JOPENSIM_SEARCH_POPULARPLACES":
						$retval['JOPENSIM_SEARCH_POPULARPLACES'] = $this->searchpopular($searchterm);
					break;
					case "JOPENSIM_SEARCH_REGIONS":
						$retval['JOPENSIM_SEARCH_REGIONS'] = $this->searchregions($searchterm);
					break;
				}
			}
			return $retval;
		} else {
			return null;
		}
	}

	public function searchobjects($searchterm) {
		$db =& JFactory::getDBO();
		$unknown = JText::_('JOPENSIM_SEARCH_UNKNOWN');
		$query = sprintf("SELECT
							#__opensim_search_objects.*,
							IFNULL(#__opensim_search_regions.regionname,'%2\$s') AS region
						FROM
							#__opensim_search_objects LEFT JOIN #__opensim_search_regions USING(regionuuid)
						WHERE
							#__opensim_search_objects.name LIKE '%%%1\$s%%'
						OR
							#__opensim_search_objects.description LIKE '%%%1\$s%%'
						ORDER BY
							#__opensim_search_objects.name",$searchterm,$unknown);
		$db->setQuery($query);
		$searchresults = $db->loadAssocList();
		if(is_array($searchresults) && count($searchresults) > 0) {
			foreach($searchresults AS $key => $searchresult) {
				$location = explode("/",$searchresult['location']);
				$searchresults[$key]['surl']		= $searchresult['region']."/".intval($location[0])."/".intval($location[1])."/".intval($location[2]);
				$searchresults[$key]['regionname']	= $searchresult['region'];
			}
			return $searchresults;
		} else {
			return array();
		}
	}

	public function searchparcels($searchterm) {
		$db =& JFactory::getDBO();
		$unknown = JText::_('JOPENSIM_SEARCH_UNKNOWN');
		$query = sprintf("SELECT
							#__opensim_search_parcels.*,
							#__opensim_search_parcels.parcelname AS name,
							IFNULL(#__opensim_search_regions.regionname,'%2\$s') AS region
						FROM
							#__opensim_search_parcels LEFT JOIN #__opensim_search_regions ON #__opensim_search_parcels.regionUUID = #__opensim_search_regions.regionuuid
						WHERE
							#__opensim_search_parcels.parcelname LIKE '%%%1\$s%%'
						OR
							#__opensim_search_parcels.description LIKE '%%%1\$s%%'
						ORDER BY
							#__opensim_search_parcels.parcelname",$searchterm,$unknown);
		$db->setQuery($query);
		$searchresults = $db->loadAssocList();
		if(is_array($searchresults) && count($searchresults) > 0) {
			foreach($searchresults AS $key => $searchresult) {
				$location = explode("/",$searchresult['landingpoint']);
				$searchresults[$key]['surl']		= $searchresult['region']."/".intval($location[0])."/".intval($location[1])."/".intval($location[2]);
				$searchresults[$key]['regionname']	= $searchresult['region'];
			}
			return $searchresults;
		} else {
			return array();
		}
	}

	public function searchparcelsale($searchterm) {
		$db =& JFactory::getDBO();
		$unknown = JText::_('JOPENSIM_SEARCH_UNKNOWN');
		$query = sprintf("SELECT
							#__opensim_search_parcelsales.*,
							#__opensim_search_parcelsales.parcelname AS name,
							IFNULL(#__opensim_search_regions.regionname,'%2\$s') AS region
						FROM
							#__opensim_search_parcelsales LEFT JOIN #__opensim_search_regions ON #__opensim_search_parcelsales.regionUUID = #__opensim_search_regions.regionuuid
						WHERE
							#__opensim_search_parcelsales.parcelname LIKE '%%%1\$s%%'
						ORDER BY
							#__opensim_search_parcelsales.parcelname",$searchterm,$unknown);
		$db->setQuery($query);
		$searchresults = $db->loadAssocList();
		if(is_array($searchresults) && count($searchresults) > 0) {
			foreach($searchresults AS $key => $searchresult) {
				$location = explode("/",$searchresult['landingpoint']);
				$searchresults[$key]['surl']		= $searchresult['region']."/".intval($location[0])."/".intval($location[1])."/".intval($location[2]);
				$searchresults[$key]['regionname']	= $searchresult['region'];
			}
			return $searchresults;
		} else {
			return array();
		}
	}

	public function searchpopular($searchterm) {
		return array();
	}

	public function searchregions($searchterm) {
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT
							#__opensim_search_regions.*,
							#__opensim_search_regions.regionname AS name,
							CONCAT(#__opensim_search_regions.regionname,'/') AS surl
						FROM
							#__opensim_search_regions
						WHERE
							#__opensim_search_regions.regionname LIKE '%%%1\$s%%'
						ORDER BY
							 #__opensim_search_regions.regionname",$searchterm);
		$db->setQuery($query);
		$searchresults = $db->loadAssocList();
		if(is_array($searchresults) && count($searchresults) > 0) {
			return $searchresults;
		} else {
			return array();
		}
	}

	public function getResultlines($results) {
		if(is_array($results) && count($results) > 0) {
			$retval = array();
			foreach($results AS $option => $result) {
				switch($option) {
					case "JOPENSIM_SEARCH_OBJECTS":
						if(count($result) > 0) {
							$retval['JOPENSIM_SEARCH_OBJECTS'][]			= sprintf("<div class='jopensim_searchresult_caption'>%s</div><div class='jopensim_searchresult_caption'>%s</div>",JText::_('JOPENSIM_SEARCHTITLE_OBJECT'),JText::_('JOPENSIM_SEARCHTITLE_REGION'));
							foreach($result AS $line) {
								$retval['JOPENSIM_SEARCH_OBJECTS'][]		= sprintf("<div class='jopensim_searchresult_td'><a href='secondlife:/"."/%s'>%s</a></div><div class='jopensim_searchresult_td'>%s</div>",$line['surl'],$line['name'],$line['region']);
							}
						} else {
							$retval['JOPENSIM_SEARCH_OBJECTS'][] = JText::_('JOPENSIM_SEARCH_NOTHINGFOUND');
						}
					break;
					case "JOPENSIM_SEARCH_PARCELS":
						if(count($result) > 0) {
							$retval['JOPENSIM_SEARCH_PARCELS'][]			= sprintf("<div class='jopensim_searchresult_caption'>%s</div><div class='jopensim_searchresult_caption'>%s</div>",JText::_('JOPENSIM_SEARCHTITLE_PARCEL'),JText::_('JOPENSIM_SEARCHTITLE_REGION'));
							foreach($result AS $line) {
								$retval['JOPENSIM_SEARCH_PARCELS'][]		= sprintf("<div class='jopensim_searchresult_td'><a href='secondlife:/"."/%s'>%s</a></div><div class='jopensim_searchresult_td'>%s</div>",$line['surl'],$line['name'],$line['region']);
							}
						} else {
							$retval['JOPENSIM_SEARCH_PARCELS'][] = JText::_('JOPENSIM_SEARCH_NOTHINGFOUND');
						}
					break;
					case "JOPENSIM_SEARCH_PARCELSALES":
						if(count($result) > 0) {
							$retval['JOPENSIM_SEARCH_PARCELSALES'][]			= sprintf("<div class='jopensim_searchresult_caption'>%s</div><div class='jopensim_searchresult_caption'>%s</div><div class='jopensim_searchresult_caption'>%s</div><div class='jopensim_searchresult_caption'>%s</div>",JText::_('JOPENSIM_SEARCHTITLE_PARCEL'),JText::_('JOPENSIM_SEARCHTITLE_PARCELAREA'),JText::_('JOPENSIM_SEARCHTITLE_PARCELPRICE'),JText::_('JOPENSIM_SEARCHTITLE_REGION'));
							foreach($result AS $line) {
								$retval['JOPENSIM_SEARCH_PARCELSALES'][]		= sprintf("<div class='jopensim_searchresult_td'><a href='secondlife:/"."/%s'>%s</a></div><div class='jopensim_searchresult_td'>%s</div><div class='jopensim_searchresult_td'>%s</div><div class='jopensim_searchresult_td'>%s</div>",$line['surl'],$line['name'],$line['area'],$line['saleprice'],$line['region']);
							}
						} else {
							$retval['JOPENSIM_SEARCH_PARCELSALES'][] = JText::_('JOPENSIM_SEARCH_NOTHINGFOUND');
						}
					break;
					case "JOPENSIM_SEARCH_POPULARPLACES":
						if(count($result) > 0) {
							foreach($result AS $line) {
								$retval['JOPENSIM_SEARCH_POPULARPLACES'][]		= sprintf("%s",$line['name']);
							}
						} else {
							$retval['JOPENSIM_SEARCH_POPULARPLACES'][] = JText::_('JOPENSIM_SEARCH_NOTHINGFOUND');
						}
					break;
					case "JOPENSIM_SEARCH_REGIONS":
						if(count($result) > 0) {
							$retval['JOPENSIM_SEARCH_REGIONS'][]			= sprintf("<div class='jopensim_searchresult_caption'>%s</div>",JText::_('JOPENSIM_SEARCHTITLE_REGION'));
							foreach($result AS $line) {
								$retval['JOPENSIM_SEARCH_REGIONS'][]		= sprintf("<div class='jopensim_searchresult_td'><a href='secondlife:/"."/%s'>%s</a></div>",$line['surl'],$line['name']);
							}
						} else {
							$retval['JOPENSIM_SEARCH_REGIONS'][] = JText::_('JOPENSIM_SEARCH_NOTHINGFOUND');
						}
					break;
				}
			}
			return $retval;
		} else {
			return null;
		}
		
	}


}
?>