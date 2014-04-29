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

class OpenSimCpModelGroups extends OpenSimCpModelOpenSim {

	var $_data;
	var $filename = "groups.php";
	var $view = "groups";

	function __construct() {
		parent::__construct();
	}

	function getGroupDetails($groupID = null) {
		$opensim = $this->opensim;
		$db =& JFactory::getDBO();
		if($groupID) $groupWhere = sprintf("WHERE #__opensim_group.GroupID = '%s'",$groupID);
		else $groupWhere = "";
		$query = sprintf("SELECT
							COUNT(DISTINCT(#__opensim_grouprolemembership.AgentID)) AS owners,
							COUNT(DISTINCT(#__opensim_groupmembership.AgentID)) AS members,
							#__opensim_group.*
						FROM
							#__opensim_group
								LEFT JOIN #__opensim_grouprolemembership ON #__opensim_group.GroupID = #__opensim_grouprolemembership.GroupID AND #__opensim_group.OwnerRoleID = #__opensim_grouprolemembership.RoleID
								LEFT JOIN #__opensim_groupmembership ON #__opensim_group.GroupID = #__opensim_groupmembership.GroupID
						%s
						GROUP BY
							#__opensim_group.GroupID
						ORDER BY
							#__opensim_group.`Name` ASC",$groupWhere);
		$db->setQuery($query);
		$groupList = $db->loadAssocList();
		// get the Founders Name
		if(is_array($groupList)) {
			foreach($groupList AS $key => $group) {
				$founderdata = $opensim->getUserData($group['FounderID']);
				if(!isset($founderdata['firstname'])) $founderdata['firstname'] = JText::_('JOPENSIMUNKNOWN');
				if(!isset($founderdata['lastname'])) $founderdata['lastname'] = JText::_('JOPENSIMUNKNOWN');
				$groupList[$key]['FounderName'] = $founderdata['firstname']." ".$founderdata['lastname'];
			}
		} else {
			$groupList = array(); // no groups, return an empty array
		}
		return $groupList;
	}

	function getGroupMembers($groupID) {
		$opensim = $this->opensim;
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT * FROM #__opensim_groupmembership WHERE GroupID = '%s'",$groupID);
		$db->setQuery($query);
		$memberList = $db->loadAssocList();
		// get the Founders Name
		foreach($memberList AS $key => $member) {
			$memberdata = $opensim->getUserData($member['AgentID']);
			$memberList[$key]['MemberName'] = $memberdata['firstname']." ".$memberdata['lastname'];
		}
		return $memberList;
	}

	function assignOwner($groupID,$roleID,$memberID) {
		$db =& JFactory::getDBO();
		$query = sprintf("INSERT INTO #__opensim_grouprolemembership (GroupID,RoleID,AgentID) VALUES ('%s','%s','%s')",$groupID,$roleID,$memberID);
		$db->setQuery($query);
		$db->query();
		return $db->getAffectedRows();
	}

	function deleteGroups($groupArray) { // deletes all groups in $groupArray
		if(!is_array($groupArray) || count($groupArray) == 0) return 0; // no groups to delete?
		$count = 0;
		foreach($groupArray AS $group) {
			$count += $this->deleteGroup($group);
		}
		return $count;
	}

	function deleteGroup($group) { // deletes the single group $group and returns the affectedRows at success
		$db =& JFactory::getDBO();
		$query = sprintf("DELETE FROM #__opensim_grouprolemembership WHERE GroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		$query = sprintf("DELETE FROM #__opensim_grouprole WHERE GroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		$query = sprintf("DELETE FROM #__opensim_groupnotice WHERE GroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		$query = sprintf("DELETE FROM #__opensim_groupmembership WHERE GroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		$query = sprintf("DELETE FROM #__opensim_groupinvite WHERE GroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		$query = sprintf("DELETE FROM #__opensim_groupactive WHERE ActiveGroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		$query = sprintf("DELETE FROM #__opensim_group WHERE GroupID = '%s'",$group);
		$db->setQuery($query);
		$db->query();
		return $db->getAffectedRows();
	}
}
?>
