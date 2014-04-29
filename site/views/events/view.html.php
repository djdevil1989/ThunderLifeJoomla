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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.html.parameter' );

class opensimViewevents extends JView {

	function display($tpl = null) {
		JHTML::stylesheet('opensim.css','components/com_opensim/assets/');
		$params		= &JComponentHelper::getParams('com_opensim');
		$debug		= var_export($params,TRUE);
		$this->assignRef('component_params',$debug);

		$layout		= JRequest::getVar('layout');
		$this->assignRef('layout',$layout);

		$model		= $this->getModel('events');
		$setting	= $model->getSettingsData();
		$opensim	= $model->opensim;

		$itemid		= JRequest::getVar('Itemid');
		$this->assignRef('Itemid',$itemid);

		$menu		= &JSite::getMenu();
		$active		= $menu->getActive($itemid);

		if (is_object($active)) {
			$eventparams		= new JParameter( $active->params );
			$access_level	= $eventparams->get('access_level');
			$group_access	= $eventparams->get('group_access');
			$pageclass_sfx	= $eventparams->get('pageclass_sfx');
			if($access_level < 3 && is_array($group_access) && count($group_access) > 0) {
				$groupflag = $model->getGroupAccessFlag($group_access);
			} else {
				$groupflag = null;
			}
		} else {
			$access_level	= null;
			$group_access	= null;
			$pageclass_sfx	= "";
		}
		$this->assignRef('pageclass_sfx',$pageclass_sfx);

		$assetinfo = pathinfo(JPATH_COMPONENT);
		$assetpath = "components".DS.$assetinfo['basename'].DS."assets".DS;
		$this->assignRef('assetpath',$assetpath);

		$duration = $model->getDurations();
		$this->assignRef('duration',$duration);

		$user		=& JFactory::getUser();
		$created	= $model->opensimIsCreated(); // check if this user has already a related OpenSim account
		$osdata		= $model->getUserData($created);

		if(!array_key_exists("timezone",$osdata) || !$osdata['timezone']) $osdata['timezone'] = $setting['eventtimedefault'];

		switch($layout) {
			case "submitevent":
		
		//		$osdata['eventparams']			= $eventparams;
				$osdata['created']				= $created;
		//		$osdata['groupflags']		= $groupflags;
				$ownerLand					= $model->getOwnerLand($created); // check the landowner access for this user
				$osdata['ownerLand']		= $ownerLand;
				$landGroups					= $model->getGroupLand($created,$groupflag); // check if the user is member of any group with land (=event hosting) access
				$osdata['landGroups']		= $landGroups;
				$publicLand					= $model->getPublicLand(); // Public regions do have '1' in the column `public` of the table #__opensim_mapinfo
				$osdata['publicLand']		= $publicLand;
		
				$landoptions = array();
				$landselected = 0;
				$preselectedLand = JRequest::getVar('eventlocation');
		
				if($access_level <= 1) {
					if(is_array($publicLand) && count($publicLand) > 0) {
						$landoption[] = "<option value='?' disabled='disabled'>".JText::_('EVENTLANDPUBLIC')."</option>\n";
						foreach($publicLand AS $land) {
							$landinfo = $opensim->getLandInfo($land);
							if($landselected == 0 && $preselectedLand == $land) $selected = " selected='selected'";
							else $selected = "";
							$landoption[] = "<option value='".$land."'".$selected.">".$landinfo['name']."</option>\n";
						}
					}
				}
		
				if($access_level <= 2) {
					if(is_array($landGroups) && count($landGroups) > 0) {
						$landoption[] = "<option value='?' disabled='disabled'>".JText::_('EVENTLANDGROUP')."</option>\n";
						foreach($landGroups AS $land) {
							$landinfo = $opensim->getLandInfo($land);
							if($landselected == 0 && $preselectedLand == $land) $selected = " selected='selected'";
							else $selected = "";
							$landoption[] = "<option value='".$land."'".$selected.">".$landinfo['name']."</option>\n";
						}
					}
				}
		
				if($access_level <= 3) {
					if(is_array($ownerLand) && count($ownerLand) > 0) {
						$landoption[] = "<option value='?' disabled='disabled'>".JText::_('EVENTLANDOWNER')."</option>\n";
						foreach($ownerLand AS $land) {
							$landinfo = $opensim->getLandInfo($land);
							if($landselected == 0 && $preselectedLand == $land) $selected = " selected='selected'";
							else $selected = "";
							$landoption[] = "<option value='".$land."'".$selected.">".$landinfo['name']."</option>\n";
						}
					}
				}
		
				$this->assignRef('landoption',$landoption);
		
				$timezone_identifiers = DateTimeZone::listIdentifiers();
				$this->assignRef('timezones',$timezone_identifiers);
				if(!array_key_exists('eventtimedefault',$setting) || !$setting['eventtimedefault']) $setting['eventtimedefault'] = "UTC";

				$this->assignRef('currencyenabled',$model->currencyenabled);
		
				$this->assignRef('eventcategories',$model->getEventCategory());
		
				switch($task) {
					default:
						// calculate the next full hour for the users timezone
						$timestamp				= time();
						$timeCompound			= date("Y-m-d H:i:s");
						$dateTimeZone			= new DateTimeZone($osdata['timezone']);
						$dateTimeZoneUTC		= new DateTimeZone('UTC');
						$dateTime				= new DateTime($timeCompound, $dateTimeZone);
						$dateTimeUTC			= new DateTime($timeCompound, $dateTimeZoneUTC);
						$timestampUTC			= $dateTimeUTC->format("U");
						$differenceServerUTC	= $timestamp - $timestampUTC;
						$timeOffset				= $dateTimeZone->getOffset($dateTimeUTC);
						$utcDifference			= $timestamp + $differenceServerUTC + $timeOffset;
		
						$nexthour	= date("H",$utcDifference)+1;
						$nexthour	= str_pad($nexthour,2,"0",STR_PAD_LEFT).":00";
						$eventdata['name']			= "";
						$eventdata['eventdate']		= date("d/m/Y");
						$eventdata['eventtime']		= $nexthour;
						$eventdata['duration']		= 0;
						$eventdata['category']		= '29';
						$eventdata['covercharge']	= 0;
						$eventdata['description']	= "";
						$eventdata['mature']		= FALSE;
						$this->assignRef('formtitle',JText::_('JOPENSIM_EVENT_CREATE'));
						$formaction					= "insertevent";
					break;
				}
			break;
			case "eventlist":
				$param['uuid'] = $created;
				$eventlist = $model->getEventList($param);
				$this->assignRef('eventlist',$eventlist);
				$this->assignRef('usertimezone',$osdata['timezone']);
				
			break;
		}
		$this->assignRef('created',$created);
		$this->assignRef('osdata',$osdata);
		$this->assignRef('formaction',$formaction);
		$this->assignRef('eventdata',$eventdata);
		parent::display($tpl);
	}
}

?>