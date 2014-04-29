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

class opensimcpViewgroups extends JView {
	function display($tpl = null) {
		JHTML::_('behavior.modal');
		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );

		$model = $this->getModel('groups');

		$assetinfo = pathinfo(JPATH_COMPONENT_ADMINISTRATOR);
		$assetpath = "components".DS.$assetinfo['basename'].DS."assets".DS;
		$this->assignRef('assetpath',$assetpath);
		$settingsdata = $model->getSettingsData();
		$settingsdata['groupaddon'] = $settingsdata['addons'] & 4;
		if(!$settingsdata['groupaddon']) {
			JFactory::getApplication()->enqueueMessage(JText::_('JOPENSIM_GROUPADDONDISABLED'),'warning');
		}

		$task = JRequest::getVar( 'task', '', 'method', 'string');

		switch($task) {
			default:
				$ueberschrift = JText::_('ADDONS_GROUPS');
				$groupList = $model->getGroupDetails();
				$this->assignRef( 'groupList',$groupList);
				$this->assignRef( 'groupquery',$groupquery);
			break;
			case "charta":
				$groupID = JRequest::getVar('groupID','','method','string');
				$groupDetails = $model->getGroupDetails($groupID);
				$charta = $groupDetails[0]['Charter'];
				$this->assignRef( 'charta',$charta);
				$tpl = "charta";
			break;
			case "grouprepair":
				$groupID = JRequest::getVar('groupID','','method','string');
				$groupDetails = $model->getGroupDetails($groupID);
				$groupMembers = $model->getGroupMembers($groupID);
				$this->assignRef( 'groupDetails',$groupDetails[0]);
				$this->assignRef( 'groupMembers',$groupMembers);
				$tpl = "repair";
			break;
		}

		$this->assignRef( 'ueberschrift',$ueberschrift);

		$this->_setToolbar($tpl);
		parent::display($tpl);
	}

	function _setToolbar($tpl) {
		JToolBarHelper::title(JText::_('OPENSIM')." ".JText::_('ADDONS_GROUPS'),'osgroup');
		switch($tpl) {
			default:
				JToolBarHelper::deleteList(JText::_('DELETEGROUPSSURE'),"deleteGroups",JText::_('DELETEGROUPS'),true,false);
			break;
		}
	}
}

?>