<?php
/***********************************************************************
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *

xmlrpc functions for group handling

adopted flotsam groups

 * @component jOpenSim (Communication Interface with the OpenSim Server)
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html

***********************************************************************/

$groupWriteKey = $grp_writekey;
$groupReadKey  = $grp_readkey;

$membersVisibleTo = 'Group'; // Anyone in the group can see members
// $membersVisibleTo = 'Owners'; // Only members of the owners role can see members
// $membersVisibleTo = 'All'; // Anyone can see members

$groupPowers = array(
	'None' => '0',
	/// <summary>Can send invitations to groups default role</summary>
	'Invite' => '2',
	/// <summary>Can eject members from group</summary>
	'Eject' => '4',
	/// <summary>Can toggle 'Open Enrollment' and change 'Signup fee'</summary>
	'ChangeOptions' => '8',
	/// <summary>Can create new roles</summary>
	'CreateRole' => '16',
	/// <summary>Can delete existing roles</summary>
	'DeleteRole' => '32',
	/// <summary>Can change Role names, titles and descriptions</summary>
	'RoleProperties' => '64',
	/// <summary>Can assign other members to assigners role</summary>
	'AssignMemberLimited' => '128',
	/// <summary>Can assign other members to any role</summary>
	'AssignMember' => '256',
	/// <summary>Can remove members from roles</summary>
	'RemoveMember' => '512',
	/// <summary>Can assign and remove abilities in roles</summary>
	'ChangeActions' => '1024',
	/// <summary>Can change group Charter, Insignia, 'Publish on the web' and which
	/// members are publicly visible in group member listings</summary>
	'ChangeIdentity' => '2048',
	/// <summary>Can buy land or deed land to group</summary>
	'LandDeed' => '4096',
	/// <summary>Can abandon group owned land to Governor Linden on mainland, or Estate owner for
	/// private estates</summary>
	'LandRelease' => '8192',
	/// <summary>Can set land for-sale information on group owned parcels</summary>
	'LandSetSale' => '16384',
	/// <summary>Can subdivide and join parcels</summary>
	'LandDivideJoin' => '32768',
	/// <summary>Can join group chat sessions</summary>
	'JoinChat' => '65536',
	/// <summary>Can toggle "Show in Find Places" and set search category</summary>
	'FindPlaces' => '131072',
	/// <summary>Can change parcel name, description, and 'Publish on web' settings</summary>
	'LandChangeIdentity' => '262144',
	/// <summary>Can set the landing point and teleport routing on group land</summary>
	'SetLandingPoint' => '524288',
	/// <summary>Can change music and media settings</summary>
	'ChangeMedia' => '1048576',
	/// <summary>Can toggle 'Edit Terrain' option in Land settings</summary>
	'LandEdit' => '2097152',
	/// <summary>Can toggle various About Land > Options settings</summary>
	'LandOptions' => '4194304',
	/// <summary>Can always terraform land, even if parcel settings have it turned off</summary>
	'AllowEditLand' => '8388608',
	/// <summary>Can always fly while over group owned land</summary>
	'AllowFly' => '16777216',
	/// <summary>Can always rez objects on group owned land</summary>
	'AllowRez' => '33554432',
	/// <summary>Can always create landmarks for group owned parcels</summary>
	'AllowLandmark' => '67108864',
	/// <summary>Can use voice chat in Group Chat sessions</summary>
	'AllowVoiceChat' => '134217728',
	/// <summary>Can set home location on any group owned parcel</summary>
	'AllowSetHome' => '268435456',
	/// <summary>Can modify public access settings for group owned parcels</summary>
	'LandManageAllowed' => '536870912',
	/// <summary>Can manager parcel ban lists on group owned land</summary>
	'LandManageBanned' => '1073741824',
	/// <summary>Can manage pass list sales information</summary>
	'LandManagePasses' => '2147483648',
	/// <summary>Can eject and freeze other avatars on group owned land</summary>
	'LandEjectAndFreeze' => '4294967296',
	/// <summary>Can return objects set to group</summary>
	'ReturnGroupSet' => '8589934592',
	/// <summary>Can return non-group owned/set objects</summary>
	'ReturnNonGroup' => '17179869184',
	/// <summary>Can landscape using Linden plants</summary>
	'LandGardening' => '34359738368',
	/// <summary>Can deed objects to group</summary>
	'DeedObject' => '68719476736',
	/// <summary>Can moderate group chat sessions</summary>
	'ModerateChat' => '137438953472',
	/// <summary>Can move group owned objects</summary>
	'ObjectManipulate' => '274877906944',
	/// <summary>Can set group owned objects for-sale</summary>
	'ObjectSetForSale' => '549755813888',
	/// <summary>Pay group liabilities and receive group dividends</summary>
	'Accountable' => '1099511627776',
	/// <summary>Can send group notices</summary>
	'SendNotices'    => '4398046511104',
	/// <summary>Can receive group notices</summary>
	'ReceiveNotices' => '8796093022208',
	/// <summary>Can create group proposals</summary>
	'StartProposal' => '17592186044416',
	/// <summary>Can vote on group proposals</summary>
	'VoteOnProposal' => '35184372088832',
	/// <summary>Can return group owned objects</summary>
	'ReturnGroupOwned' => '281474976710656',

	/// <summary>Members are visible to non-owners</summary>
	'RoleMembersVisible' => '140737488355328',

	// changed to here for easier change later
//	'everyonePowers' => '8796495740928' // This should now be fixed, when libomv was updated...
	'everyonePowers' => '203410053857280'
						 
);

// This is filled in by secure()
$uuidZero			= "00000000-0000-0000-0000-000000000000";
$requestingAgent	= $uuidZero;
$common_sig			= array(array($xmlrpcStruct, $xmlrpcStruct));

$xmlrpcserver = new jxmlrpc_server(array(
		// Group Functions
			"groups.createGroup"				=> array("function" => "createGroup",				"signature" => $common_sig),
			"groups.updateGroup"				=> array("function" => "updateGroup",				"signature" => $common_sig),
			"groups.getGroup"					=> array("function" => "getGroup",					"signature" => $common_sig),
			"groups.findGroups"					=> array("function" => "findGroups",				"signature" => $common_sig),
			"groups.getGroupRoles"				=> array("function" => "getGroupRoles",				"signature" => $common_sig),
			"groups.addRoleToGroup"				=> array("function" => "addRoleToGroup",			"signature" => $common_sig),
			"groups.removeRoleFromGroup"		=> array("function" => "removeRoleFromGroup",		"signature" => $common_sig),
			"groups.updateGroupRole"			=> array("function" => "updateGroupRole",			"signature" => $common_sig),
			"groups.getGroupRoleMembers"		=> array("function" => "getGroupRoleMembers",		"signature" => $common_sig),
			"groups.setAgentGroupSelectedRole"	=> array("function" => "setAgentGroupSelectedRole",	"signature" => $common_sig),
			"groups.addAgentToGroupRole"		=> array("function" => "addAgentToGroupRole",		"signature" => $common_sig),
			"groups.removeAgentFromGroupRole"	=> array("function" => "removeAgentFromGroupRole",	"signature" => $common_sig),
			"groups.getGroupMembers"			=> array("function" => "getGroupMembers",			"signature" => $common_sig),
			"groups.addAgentToGroup"			=> array("function" => "addAgentToGroup",			"signature" => $common_sig),
			"groups.removeAgentFromGroup"		=> array("function" => "removeAgentFromGroup",		"signature" => $common_sig),
			"groups.setAgentGroupInfo"			=> array("function" => "setAgentGroupInfo",			"signature" => $common_sig),
			"groups.addAgentToGroupInvite"		=> array("function" => "addAgentToGroupInvite",		"signature" => $common_sig),
			"groups.getAgentToGroupInvite"		=> array("function" => "getAgentToGroupInvite",		"signature" => $common_sig),
			"groups.removeAgentToGroupInvite"	=> array("function" => "removeAgentToGroupInvite",	"signature" => $common_sig),
			"groups.setAgentActiveGroup"		=> array("function" => "setAgentActiveGroup",		"signature" => $common_sig),
			"groups.getAgentGroupMembership"	=> array("function" => "getAgentGroupMembership",	"signature" => $common_sig),
			"groups.getAgentGroupMemberships"	=> array("function" => "getAgentGroupMemberships",	"signature" => $common_sig),
			"groups.getAgentActiveMembership"	=> array("function" => "getAgentActiveMembership",	"signature" => $common_sig),
			"groups.getAgentRoles"				=> array("function" => "getAgentRoles",				"signature" => $common_sig),
			"groups.getGroupNotices"			=> array("function" => "getGroupNotices",			"signature" => $common_sig),
			"groups.getGroupNotice"				=> array("function" => "getGroupNotice",			"signature" => $common_sig),
			"groups.addGroupNotice"				=> array("function" => "addGroupNotice",			"signature" => $common_sig),

			"groups.test"						=> array("function" => "test"),
	), false);

$jconfig = new JConfig();

$conn['dbHost']		= $jconfig->host;
$conn['dbUser']		= $jconfig->user;
$conn['dbPassword']	= $jconfig->password;
$conn['dbName']		= $jconfig->db;
$conn['prefix']		= $jconfig->dbprefix;

$tbl_osgroup				= $conn['prefix']."opensim_group";
$tbl_osagent				= $conn['prefix']."opensim_groupactive";
$tbl_osgroupinvite			= $conn['prefix']."opensim_groupinvite";
$tbl_osgroupmembership		= $conn['prefix']."opensim_groupmembership";
$tbl_osgroupnotice			= $conn['prefix']."opensim_groupnotice";
$tbl_osgrouprolemembership	= $conn['prefix']."opensim_grouprolemembership";
$tbl_osrole					= $conn['prefix']."opensim_grouprole";


/*error_log("XML-RPC call mit Methode ".$method);*/

$groupDBCon = mysql_connect($conn['dbHost'],$conn['dbUser'],$conn['dbPassword']);

// debugzeile2("\n\ngroupDBCon in ".__FILE__." bei Zeile ".__LINE__.":\n\n".var_export($groupDBCon,TRUE)."\n");

if (!$groupDBCon) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($conn['dbName'], $groupDBCon);


// debugzeile2("\n\nconn in ".__FILE__." bei Zeile ".__LINE__.":\n\n".var_export($conn,TRUE)."\n");
// debugzeile2("\n\ngroupDBCon in ".__FILE__." bei Zeile ".__LINE__.":\n\n".var_export($groupDBCon,TRUE)."\n");

function createGroup($params) {
//	groupdebug($params);
	if(is_array($error = secureRequest($params, TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon,$groupPowers;

	$groupID		= $params["GroupID"];
	$name			= mysqlsafestring($params["Name"]);
	$charter		= utf8_encode(mysqlsafestring($params["Charter"]));
	$insigniaID		= $params["InsigniaID"];
	$founderID		= $params["FounderID"];
	$membershipFee	= $params["MembershipFee"];
	$openEnrollment	= mysqlsafestring($params["OpenEnrollment"]);
	$showInList		= $params["ShowInList"];
	$allowPublish	= $params["AllowPublish"];
	$maturePublish	= $params["MaturePublish"];
	$ownerRoleID	= $params["OwnerRoleID"];
	$everyonePowers	= (intval($params["EveryonePowers"]) | intval($groupPowers['RoleMembersVisible']));
	$ownersPowers	= $params["OwnersPowers"];

/*
	$debug['everyonePowers'] = $everyonePowers;
	$debug['params-EveryonePowers'] = $params["EveryonePowers"];
	$debug['groupPowers-RoleMembersVisible'] = $groupPowers['RoleMembersVisible'];
	groupdebug($debug);
*/	

	// Create group
	$sql = sprintf("INSERT INTO %1\$s
						(GroupID, Name, Charter, InsigniaID, FounderID, MembershipFee, OpenEnrollment, ShowInList, AllowPublish, MaturePublish, OwnerRoleID)
					VALUES
						('%2\$s', '%3\$s', '%4\$s', '%5\$s', '%6\$s', '%7\$d', '%8\$s', '%9\$d', '%10\$d', '%11\$d', '%12\$s')",
			$tbl_osgroup,
			$groupID,
			$name,
			$charter,
			$insigniaID,
			$founderID,
			$membershipFee,
			$openEnrollment,
			$showInList,
			$allowPublish,
			$maturePublish,
			$ownerRoleID);

	if (!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	// Create Everyone Role
	// NOTE: FIXME: This is a temp fix until the libomv enum for group powers is fixed in OpenSim

	$result = _addRoleToGroup(array('GroupID' => $groupID,
									'RoleID' => $uuidZero,
									'Name' => 'Everyone',
									'Description' => 'Everyone in the group is in the everyone role.',
									'Title' => "Member of $name",
									'Powers' => $everyonePowers));
	if(isset($result['error'])) {
		return $result;
	}

	// Create Owner Role
	$result = _addRoleToGroup(array('GroupID' => $groupID,
									'RoleID' => $ownerRoleID,
									'Name' => 'Owners',
									'Description' => "Owners of $name",
									'Title' => "Owner of $name",
									'Powers' => $ownersPowers));
	if(isset($result['error'])) {
		return $result;
	}

	// Add founder to group, will automatically place them in the Everyone Role, also places them in specified Owner Role
	$result = _addAgentToGroup(array('AgentID' => $founderID,
									 'GroupID' => $groupID,
									 'RoleID' => $ownerRoleID));
	if(isset($result['error'])) {
		return $result;
	}

	// Select the owner's role for the founder
	$result = _setAgentGroupSelectedRole(array('AgentID' => $founderID,
											   'RoleID' => $ownerRoleID,
											   'GroupID' => $groupID));
	if(isset($result['error'])) {
		return $result;
	}

	// Set the new group as the founder's active group
	$result = _setAgentActiveGroup(array('AgentID' => $founderID,
										 'GroupID' => $groupID));
	if(isset($result['error'])) {
		return $result;
	}

	return getGroup(array("GroupID"=>$groupID));
}

// Private method, does not include security, to only be called from places that have already verified security
function _addRoleToGroup($params) {
//	groupdebug($params);
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon,$groupPowers;
	$everyonePowers = $groupPowers['everyonePowers']; // This should now be fixed, when libomv was updated...

	$groupID = $params['GroupID'];
	$roleID  = $params['RoleID'];
	$name    = mysqlsafestring(utf8_encode($params['Name']));
	$desc    = mysqlsafestring(utf8_encode($params['Description']));
	$title   = mysqlsafestring(utf8_encode($params['Title']));
	$powers  = $params['Powers'];

	if(!isset($powers) || ($powers == 0) || ($powers == '')) {
		$powers = $everyonePowers;
	}

	$sql = sprintf("INSERT INTO %1\$s
						(GroupID, RoleID, Name, Description, Title, Powers)
					VALUES
						('%2\$s', '%3\$s', '%4\$s', '%5\$s', '%6\$s', '%7\$s')",
				$tbl_osrole,
				$groupID,
				$roleID,
				$name,
				$desc,
				$title,
				number_format($powers,0,".",""));

	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(),
					 'method' => 'addRoleToGroup',
					 'params' => var_export($params, TRUE));
	}
	return array("success" => "true");
}

function addRoleToGroup($params) {
	if(is_array($error = secureRequest($params, TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID = $params['GroupID'];

	// Verify the requesting agent has permission
	if(is_array($error = checkGroupPermission($groupID, $groupPowers['CreateRole']))) {
		return $error;
	}

	return _addRoleToGroup($params);
}

function updateGroupRole($params) {
	if(is_array($error = secureRequest($params, TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID = $params['GroupID'];
	$roleID  = $params['RoleID'];
	$name    = mysqlsafestring(utf8_encode($params['Name']));
	$desc    = mysqlsafestring(utf8_encode($params['Description']));
	$title   = mysqlsafestring(utf8_encode($params['Title']));
	$powers  = $params['Powers'];

	// Verify the requesting agent has permission
	if(is_array($error = checkGroupPermission($groupID,$groupPowers['RoleProperties']))) {
		return $error;
	}

	$sql = sprintf("UPDATE %s SET RoleID = '%s'",$tbl_osrole,$roleID);
	if(isset($params['Name'])) {
		$sql .= sprintf(", Name = '%s'",$name);
	}
	if(isset($params['Description'])) {
		$sql .= sprintf(", Description = '%s'",$desc);
	}
	if(isset($params['Title'])) {
		$sql .= sprintf(", Title = '%s'",$title);
	}
	if(isset($params['Powers'])) {
		$sql .= sprintf(", Powers = '%s'",number_format($powers,0,".",""));
	}
	$sql .= sprintf(" WHERE GroupID = '%s' AND RoleID = '%s'",$groupID,$roleID);

	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array("success" => "true");
}

function removeRoleFromGroup($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID = $params['GroupID'];
	$roleID  = $params['RoleID'];

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['RoleProperties']))) {
		return $error;
	}

	/// 1. Remove all members from Role
	/// 2. Set selected Role to uuidZero for anyone that had the role selected
	/// 3. Delete roll

	$sql = sprintf("DELETE FROM %s WHERE GroupID = '%s' AND RoleID = '%s'",
				$tbl_osgrouprolemembership,
				$groupID,
				$roleID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	$sql = sprintf("UPDATE %s SET SelectedRoleID = '%s' WHERE GroupID = '%s' AND SelectedRoleID = '%s'",
				$tbl_osgroupmembership,
				$uuidZero,
				$groupID,
				$roleID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	$sql = sprintf("DELETE FROM %s WHERE GroupID = '%s' AND RoleID = '%s'",
				$tbl_osrole,
				$groupID,
				$roleID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array("success" => "true");
}

function getGroup($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	return _getGroup($params);
}

function _getGroup($params) {
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;

	$sql = sprintf("SELECT
						%1\$s.GroupID,
						%1\$s.Name,
						Charter,
						InsigniaID,
						FounderID,
						MembershipFee,
						OpenEnrollment,
						ShowInList,
						AllowPublish,
						MaturePublish,
						OwnerRoleID,
						COUNT(%2\$s.RoleID) as GroupRolesCount,
						COUNT(%3\$s.AgentID) as GroupMembershipCount
					FROM %1\$s
						LEFT JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID)
						LEFT JOIN %3\$s ON (%1\$s.GroupID = %3\$s.GroupID)
					WHERE\n",
			  $tbl_osgroup,
			  $tbl_osrole,
			  $tbl_osgroupmembership);

	if(isset($params['GroupID'])) {
		$sql .= sprintf("\t\t%s.GroupID = '%s'",$tbl_osgroup,$params['GroupID']);
	} elseif(isset($params['Name'])) {
		$sql .= sprintf("\t\t%s.Name = '%s'",$tbl_osgroup,mysqlsafestring($params['Name']));
	} else {
		return array("error" => "Must specify GroupID or Name");
	}

	$sql .= sprintf("\nGROUP BY
							%1\$s.GroupID,
							%1\$s.Name,
							Charter,
							InsigniaID,
							FounderID,
							MembershipFee,
							OpenEnrollment,
							ShowInList,
							AllowPublish,
							MaturePublish,
							OwnerRoleID",
					$tbl_osgroup);
	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if (mysql_num_rows($result) == 0) {
		return array('succeed' => 'false', 'error' => 'Group Not Found', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	return mysql_fetch_assoc($result);
}

function updateGroup($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID		= $params["GroupID"];
	$charter		= mysqlsafestring(utf8_encode($params["Charter"]));
	$insigniaID		= $params["InsigniaID"];
	$membershipFee	= $params["MembershipFee"];
	$openEnrollment	= $params["OpenEnrollment"];
	$showInList		= $params["ShowInList"];
	$allowPublish	= $params["AllowPublish"];
	$maturePublish	= $params["MaturePublish"];

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['ChangeOptions']))) {
		return $error;
	}

	// Update group
	$sql = sprintf("UPDATE %s
					SET
						Charter			= '%s',
						InsigniaID		= '%s',
						MembershipFee	= '%d',
						OpenEnrollment	= '%s',
						ShowInList		= '%d',
						AllowPublish	= '%d',
						MaturePublish	= '%d'
					WHERE
						GroupID = '$groupID'",
						$tbl_osgroup,
						$charter,
						$insigniaID,
						$membershipFee,
						$openEnrollment,
						$showInList,
						$allowPublish,
						$maturePublish);

	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array('success' => 'true');
}


function findGroups($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}
	if(!isset($params['Search']) || !$params['Search']) {
		$retval['success'] = FALSE;
		$retval['message'] = "no search term provided!";
		return $retval;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$search = (isset($params['Search'])) ? mysqlsafestring($params['Search']):" ";

	$sql = sprintf("SELECT
						%1\$s.GroupID,
						%1\$s.Name,
						COUNT(%2\$s.AgentID) as Members
					FROM
						%1\$s LEFT JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID)
					WHERE
						(
							MATCH (%1\$s.name) AGAINST ('%3\$s' IN BOOLEAN MODE)
						OR
							%1\$s.name LIKE '%%%3\$s%%'
						OR
							%1\$s.name REGEXP '%3\$s'
						)
					AND
						ShowInList = 1
					GROUP BY
						$tbl_osgroup.GroupID, $tbl_osgroup.Name",
				$tbl_osgroup,
				$tbl_osgroupmembership,
				$search);

	$result = mysql_query($sql,$groupDBCon);

	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($result) == 0) {
		return array('succeed' => 'false', 'error' => 'No groups found.', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$results = array();

	while($row = mysql_fetch_assoc($result)) {
		$groupID = $row['GroupID'];
		$results[$groupID] = $row;
	}

	return array('results' => $results, 'success' => TRUE);
}

function _setAgentActiveGroup($params) {
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$agentID = $params['AgentID'];
	$groupID = $params['GroupID'];

	$sql = sprintf("INSERT INTO %1\$s (ActiveGroupID, AgentID) VALUES ('%2\$s','%3\$s')
					ON DUPLICATE KEY UPDATE ActiveGroupID = '%2\$s', AgentID = '%3\$s'",
				$tbl_osagent,
				$groupID,
				$agentID);

	if (!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array("success" => "true");
}

function setAgentActiveGroup($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$agentID = $params['AgentID'];
	$groupID = $params['GroupID'];

	if(isset($requestingAgent) && ($requestingAgent != $uuidZero) && ($requestingAgent != $agentID)) {
		return array('error' => "Agent can only change their own Selected Group Role", 'params' => var_export($params, TRUE));
	}

	return _setAgentActiveGroup($params);
}

function addAgentToGroup($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID = $params["GroupID"];
	$agentID = $params["AgentID"];

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['AssignMember']))) {
		// If they don't have direct permission, check to see if the group is marked for open enrollment
		$groupInfo = _getGroup(array('GroupID'=>$groupID));

		if(isset($groupInfo['error'])) {
			return $groupInfo;
		}

		if($groupInfo['OpenEnrollment'] != 1) {
			// Group is not open enrollment, check if the specified agentid has an invite
			$sql = sprintf("SELECT
								GroupID,
								RoleID,
								AgentID
							FROM
								%1\$s
							WHERE
								%1\$s.AgentID = '%2\$s'
							AND
								%1\$s.GroupID = '%3\$s'",
				  $tbl_osgroupinvite,
				  $agentID,
				  $groupID);

			$result = mysql_query($sql,$groupDBCon);
			if (!$result) {
					return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
			}

			if(mysql_num_rows($result) == 1) {
				// if there is an invite, make sure we're adding the user to the role specified in the invite
				$inviteInfo = mysql_fetch_assoc($result);
				$params['RoleID'] = $inviteInfo['RoleID'];
			} else {
				// Not openenrollment, not invited, return permission denied error
				return $error;
			}
		}
	}

	return _addAgentToGroup($params);
}

// Private method, does not include security, to only be called from places that have already verified security
function _addAgentToGroup($params) {
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon,$groupPowers;
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;

	$agentID = $params["AgentID"];
	$groupID = $params["GroupID"];
	$roleID  = $uuidZero;
	$sessionID	= (isset($params['RequestingSessionID'])) ? $params['RequestingSessionID']:null;

	if(isset($params["RoleID"])) {
		$roleID = $params["RoleID"];
	}

	// Check if agent already a member
	$sql = sprintf("SELECT
						COUNT(AgentID) AS isMember
					FROM
						%s
					WHERE
						AgentID = '%s'
					AND
						GroupID = '%s'",
				$tbl_osgroupmembership,
				$agentID,
				$groupID);
	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	// If not a member, add membership, select role (defaults to uuidZero, or everyone role)
	if(mysql_result($result,0) == 0) {
		$query		= sprintf("SELECT MembershipFee FROM #__opensim_group WHERE GroupID = '%s'",$groupID);
		$db			=& JFactory::getDBO();
		$db->setQuery($query);
		$groupfee	= $db->loadResult();
		if($groupfee > 0) { // There is a fee set
			$currencyfunctionfile = JPATH_BASE.DS.'components'.DS.'com_opensim'.DS.'includes'.DS.'functions_currency.php'; // for jOpenSimMoney this file needs to be present
			if(is_file($currencyfunctionfile)) { // lets check if currency is enabled
				require_once($currencyfunctionfile); // get the functions
				if(defined('_JOPENSIMCURRENCY') && _JOPENSIMCURRENCY === TRUE) { // this jOpenSim has currency enabled, we need to check who gets the Enrollment fee
					$parameter['clientUUID']		= $agentID;
					$parameter['amount']			= $groupfee;
					$covered = AmountCovered($parameter);
					if($covered['success'] == TRUE) {
						$parameter['clientUUID']	= $agentID;
						$parameter['groupID']		= $groupID;
						$parameter['groupPower']	= $groupPowers['Accountable'];
						$parameter['groupFee']		= $groupfee;
						$parameter['sessionID']		= $sessionID;

						$retval = groupMembershipFee($parameter);
						if($retval['success'] === FALSE) { // Something went wrong during paying groupMembershipFee (or checking groupDividend)?
							return $retval;
						}
					} else {
						$retval = $covered;
						$retval1['covered'] = FALSE;
//						return $covered;
					}
				} else {
					$retval1['defined'] = _JOPENSIMCURRENCY;
				}
			} else {
				$retval1['script_filename'] = $_SERVER['SCRIPT_FILENAME'];
				$retval1['filenotfound'] = "Line ".__LINE__.": Currency enabled and Group has MembershipFee, but currency.php not found";
//				return $retval1;
			}
		}
		$sql = sprintf("INSERT INTO %s
							(GroupID, AgentID, Contribution, ListInProfile, AcceptNotices, SelectedRoleID)
						VALUES
							('%s','%s', 0, 1, 1,'%s')",
				$tbl_osgroupmembership,
				$groupID,
				$agentID,
				$roleID);

		if(!mysql_query($sql,$groupDBCon)) {
			return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
		}
	}

	// Make sure they're in the Everyone role
	$result = _addAgentToGroupRole(array("GroupID" => $groupID, "RoleID" => $uuidZero, "AgentID" => $agentID));
	if(isset($result['error'])) {
		return $result;
	}

	// Make sure they're in specified role, if they were invited
	if($roleID != $uuidZero) {
		$result = _addAgentToGroupRole(array("GroupID" => $groupID, "RoleID" => $roleID, "AgentID" => $agentID));
		if(isset($result['error'])) {
			return $result;
		}
	}

	//Set the role they were invited to as their selected role
	_setAgentGroupSelectedRole(array('AgentID' => $agentID, 'RoleID' => $roleID, 'GroupID' => $groupID));

	// Set the group as their active group.
	// _setAgentActiveGroup(array("GroupID" => $groupID, "AgentID" => $agentID));

	$retval['success'] = TRUE;
	if(isset($retval1)) $retval['sub_returnvalue'] = $retval1;
	return $retval;
}

function removeAgentFromGroup($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$agentID = $params["AgentID"];
	$groupID = $params["GroupID"];

	// An agent is always allowed to remove themselves from a group -- so only check if the requesting agent is different then the agent being removed.
	if($agentID != $requestingAgent) {
		if(is_array($error = checkGroupPermission($groupID,$groupPowers['RemoveMember']))) {
			return $error;
		}
	}

	// 1. If group is agent's active group, change active group to uuidZero
	// 2. Remove Agent from group (osgroupmembership)
	// 3. Remove Agent from all of the groups roles (osgrouprolemembership)

	$sql = sprintf("UPDATE %s SET ActiveGroupID = '%s' WHERE AgentID = '%s' AND ActiveGroupID = '%s'",
				$tbl_osagent,
				$uuidZero,
				$agentID,
				$groupID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	$sql = sprintf("DELETE FROM %s WHERE AgentID = '%s' AND GroupID = '%s'",
				$tbl_osgroupmembership,
				$agentID,
				$groupID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	$sql = sprintf("DELETE FROM %s WHERE AgentID = '%s' AND GroupID = '%s'",
				$tbl_osgrouprolemembership,
				$agentID,
				$groupID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array("success" => "true");
}

function _addAgentToGroupRole($params) {
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon;
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;

	$agentID = $params["AgentID"];
	$groupID = $params["GroupID"];
	$roleID  = $params["RoleID"];

	// Check if agent already a member
	$sql = sprintf("SELECT
						COUNT(AgentID) as isMember
					FROM
						%s
					WHERE
						AgentID = '%s'
					AND
						RoleID = '%s'
					AND
						GroupID = '%s'",
				$tbl_osgrouprolemembership,
				$agentID,
				$roleID,
				$groupID);
	debugzeile("\nsql in line ".__LINE__.":\n\n".var_export($sql,TRUE));
	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_result($result, 0) == 0) {
		$sql = sprintf("INSERT INTO %s
							(GroupID, RoleID, AgentID)
						VALUES
							('%s', '%s', '%s')",
				$tbl_osgrouprolemembership,
				$groupID,
				$roleID,
				$agentID);
		debugzeile("\nsql in line ".__LINE__.":\n\n".var_export($sql,TRUE));
		if(!mysql_query($sql,$groupDBCon)) {
			return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
		}
	}

	return array("success" => "true");
}

function addAgentToGroupRole($params) {
	debugzeile("\nparams in line ".__LINE__.":\n\n".var_export($params,TRUE));
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	/*$agentID = $params["AgentID"];*/
	$agentID = $params["RequestingAgentID"];
	$groupID = $params["GroupID"];
	$roleID  = $params["RoleID"];

	// Check if being assigned to Owners role, assignments to an owners role can only be requested by owners.
	$sql = sprintf("SELECT
						OwnerRoleID,
						%2\$s.AgentID
					FROM
						%1\$s LEFT JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.OwnerRoleID = %2\$s.RoleID)
					WHERE
						%2\$s.AgentID = '%3\$s'
					AND
						%1\$s.GroupID = '%4\$s'",
				$tbl_osgroup,
				$tbl_osgrouprolemembership,
				$agentID,
				$groupID);

	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($result) == 0) {
		return array('error' => "Line: ".__LINE__.": Group ($groupID) not found or Agent ($agentID) is not in the owner's role (sql: $sql)", 'params' => var_export($params, TRUE));
	}

	$ownerRoleInfo = mysql_fetch_assoc($result);
	if(($ownerRoleInfo['OwnerRoleID'] == $roleID) && ($ownerRoleInfo['AgentID'] != $requestingAgent)) {
		return array('error' => "Requesting agent $requestingAgent is not a member of the Owners Role and cannot add members to the owners role.", 'params' => var_export($params, TRUE));
	}

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['AssignMember']))) {
		return $error;
	}

	return _addAgentToGroupRole($params);
}

function removeAgentFromGroupRole($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon,$groupPowers;

	$agentID = $params["AgentID"];
	$groupID = $params["GroupID"];
	$roleID  = $params["RoleID"];

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['AssignMember']))) {
		return $error;
	}

	// If agent has this role selected, change their selection to everyone (uuidZero) role
	$sql = sprintf("UPDATE
						%s
					SET
						SelectedRoleID = '%s'
					WHERE
						AgentID = '%s'
					AND
						GroupID = '%s'
					AND
						SelectedRoleID = '%s'",
				$tbl_osgroupmembership,
				$uuidZero,
				$agentID,
				$groupID,
				$roleID);
	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	$sql = sprintf("DELETE FROM %s WHERE AgentID = '%s' AND GroupID = '%s' AND RoleID = '%s'",
				$tbl_osgrouprolemembership,
				$agentID,
				$groupID,
				$roleID);
	if(!mysql_query($sql,$groupDBCon)) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array("success" => "true");
}

function _setAgentGroupSelectedRole($params) {
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon;

	$agentID = $params["AgentID"];
	$groupID = $params["GroupID"];
	$roleID  = $params["RoleID"];

	$sql = sprintf("UPDATE %s SET SelectedRoleID = '%s' WHERE AgentID = '%s' AND GroupID = '%s'",
				$tbl_osgroupmembership,
				$roleID,
				$agentID,
				$groupID);
	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array('success' => 'true');
}

function setAgentGroupSelectedRole($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$agentID = $params["AgentID"];
	$groupID = $params["GroupID"];
	$roleID = $params["RoleID"];

	if(isset($requestingAgent) && ($requestingAgent != $uuidZero) && ($requestingAgent != $agentID)) {
		return array('error' => "Agent can only change their own Selected Group Role", 'params' => var_export($params, TRUE));
	}

	return _setAgentGroupSelectedRole($params);
}

function getAgentGroupMembership($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$groupID = $params['GroupID'];
	$agentID = $params['AgentID'];

	$sql = sprintf("SELECT
						%1\$s.GroupID,
						%1\$s.Name AS GroupName,
						%1\$s.Charter,
						%1\$s.InsigniaID,
						%1\$s.FounderID,
						%1\$s.MembershipFee,
						%1\$s.OpenEnrollment,
						%1\$s.ShowInList,
						%1\$s.AllowPublish,
						%1\$s.MaturePublish,
						%2\$s.Contribution,
						%2\$s.ListInProfile,
						%2\$s.AcceptNotices,
						%2\$s.SelectedRoleID,
						%3\$s.Title,
						%4\$s.ActiveGroupID
					FROM
						%1\$s
							JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID)
							JOIN %3\$s ON (%2\$s.SelectedRoleID = %3\$s.RoleID AND %2\$s.GroupID = %3\$s.GroupID)
							JOIN %4\$s ON (%4\$s.AgentID = %2\$s.AgentID)
					WHERE
						%1\$s.GroupID = '%5\$s'
					AND
						%2\$s.AgentID = '%6\$s'",
				$tbl_osgroup,
				$tbl_osgroupmembership,
				$tbl_osrole,
				$tbl_osagent,
				$groupID,
				$agentID);

	$groupmembershipResult = mysql_query($sql,$groupDBCon);
	if(!$groupmembershipResult) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($groupmembershipResult) == 0) {
		return array('succeed' => 'false', 'error' => 'None Found', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$groupMembershipInfo = mysql_fetch_assoc($groupmembershipResult);

	$sql = sprintf("SELECT
						BIT_OR(%2\$s.Powers) AS GroupPowers
					FROM
						%1\$s JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.RoleID = %2\$s.RoleID)
					WHERE
						%1\$s.GroupID = '%3\%s'
					AND
						%1\$s.AgentID = '%4\$s'",
				$tbl_osgrouprolemembership,
				$tbl_osrole,
				$groupID,
				$agentID);

	$groupPowersResult = mysql_query($sql,$groupDBCon);
	if(!$groupPowersResult) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}
	$groupPowersInfo = mysql_fetch_assoc($groupPowersResult);

	return array_merge($groupMembershipInfo,$groupPowersInfo);
}

function getAgentGroupMemberships($params) {
	$debug = var_export($params,TRUE);
	/*groupdebug($debug,"getAgentGroupMemberships");*/
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}
	/*groupdebug("getAgentGroupMemberships 2");*/

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	if(!isset($params['AgentID'])) return array('succeed' => 'false', 'error' => 'No AgentID provided', 'params' => var_export($params, TRUE));
	$agentID = $params['AgentID'];

	$sql = sprintf("SELECT
						%1\$s.GroupID,
						%1\$s.Name AS GroupName,
						%1\$s.Charter,
						%1\$s.InsigniaID,
						%1\$s.FounderID,
						%1\$s.MembershipFee,
						%1\$s.OpenEnrollment,
						%1\$s.ShowInList,
						%1\$s.AllowPublish,
						%1\$s.MaturePublish,
						%2\$s.Contribution,
						%2\$s.ListInProfile,
						%2\$s.AcceptNotices,
						%2\$s.SelectedRoleID,
						%3\$s.Title,
						IFNULL(%4\$s.ActiveGroupID, '%6\$s') AS ActiveGroupID
					FROM
						%1\$s
							JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID)
							JOIN %3\$s ON (%2\$s.SelectedRoleID = %3\$s.RoleID AND %2\$s.GroupID = %3\$s.GroupID)
							LEFT JOIN %4\$s ON (%4\$s.AgentID = %2\$s.AgentID)
					WHERE
						%2\$s.AgentID = '%5\$s'",
				$tbl_osgroup,
				$tbl_osgroupmembership,
				$tbl_osrole,
				$tbl_osagent,
				$agentID,
				$uuidZero);

	$groupmembershipResults = mysql_query($sql,$groupDBCon);
	if(!$groupmembershipResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($groupmembershipResults) == 0) {
		return array('succeed' => 'false', 'error' => 'No Memberships', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$groupResults = array();
	while($groupMembershipInfo = mysql_fetch_assoc($groupmembershipResults)) {
		$groupID = $groupMembershipInfo['GroupID'];
		$sql = sprintf("SELECT
							BIT_OR(%2\$s.Powers) AS GroupPowers
						FROM
							%1\$s JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.RoleID = %2\$s.RoleID)
						WHERE
							%1\$s.GroupID = '%3\$s'
						AND
							%1\$s.AgentID = '%4\$s'",
				$tbl_osgrouprolemembership,
				$tbl_osrole,
				$groupID,
				$agentID);

		$groupPowersResult = mysql_query($sql,$groupDBCon);
		if(!$groupPowersResult) {
			return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
		}
		$groupPowersInfo = mysql_fetch_assoc($groupPowersResult);
		$groupResults[$groupID] = array_merge($groupMembershipInfo,$groupPowersInfo);
	}

	$debug = var_export($groupResults,TRUE);
	/*groupdebug($debug,"getAgentGroupMemberships return");*/

	return $groupResults;
}

function canAgentViewRoleMembers($agentID,$groupID,$roleID) {
	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $membersVisibleTo,$groupDBCon;

	if($membersVisibleTo == 'All') return true;

	$sql = sprintf("SELECT
						CASE WHEN MIN(%3\$s.AgentID) IS NOT NULL THEN 1 ELSE 0 END AS IsOwner
					FROM
						%1\$s
							JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %2\$s.AgentID = '%5\$s')
							LEFT JOIN %3\$s ON (%3\$s.GroupID = %1\$s.GroupID AND %3\$s.RoleID  = %1\$s.OwnerRoleID AND %3\$s.AgentID = '%5\$s')
					WHERE
						%1\$s.GroupID = '%4\$s'
					GROUP BY
						%1\$s.GroupID",
				$tbl_osgroup,
				$tbl_osgroupmembership,
				$tbl_osgrouprolemembership,
				$groupID,
				$agentID);

//	debugzeile2("\nsql in line ".__LINE__.":\n\n".var_export($sql,TRUE));
	$viewMemberResults = mysql_query($sql,$groupDBCon);
	if(!$viewMemberResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error());
	}

	if(mysql_num_rows($viewMemberResults) == 0) {
		return false;
	}

	$viewMemberInfo = mysql_fetch_assoc($viewMemberResults);

	switch($membersVisibleTo) {
		case "Group":
			// if we get to here, there is at least one row, so they are members of the group
			return true;
		break;
		case "Owners":
		default:
			return $viewMemberInfo['IsOwner'];
		break;
	}
}

function getGroupMembers($params) {
//	debugzeile2("\nparams for getGroupMembers in line ".__LINE__.":\n\n".var_export($params,TRUE));
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID			= (isset($params['GroupID']))			? $params['GroupID']:"";
	$requestingAgentID	= (isset($params['requestingAgentID']))	? $params['requestingAgentID']:"";

	$sql = sprintf("SELECT
						%2\$s.AgentID,
						%2\$s.Contribution,
						%2\$s.ListInProfile,
						%2\$s.AcceptNotices,
						%2\$s.SelectedRoleID,
						%3\$s.Title,
						CASE WHEN %4\$s.AgentID IS NOT NULL THEN 1 ELSE 0 END AS IsOwner
					FROM
						%1\$s
							JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID)
							JOIN %3\$s ON (%2\$s.SelectedRoleID = %3\$s.RoleID AND %2\$s.GroupID = %3\$s.GroupID)
							JOIN %3\$s AS OwnerRole ON (%1\$s.OwnerRoleID  = OwnerRole.RoleID AND %1\$s.GroupID  = OwnerRole.GroupID)
							LEFT JOIN %4\$s ON (%1\$s.OwnerRoleID = %4\$s.RoleID AND %1\$s.GroupID = %4\$s.GroupID AND %2\$s.AgentID = %4\$s.AgentID)
					WHERE
						%1\$s.GroupID = '%5\$s'",
				$tbl_osgroup,
				$tbl_osgroupmembership,
				$tbl_osrole,
				$tbl_osgrouprolemembership,
				$groupID);

	$groupmemberResults = mysql_query($sql,$groupDBCon);
	if(!$groupmemberResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($groupmemberResults) == 0) {
		return array('succeed' => 'false', 'error' => 'No Group Members found', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$roleMembersVisibleBit = $groupPowers['RoleMembersVisible'];
	$canViewAllGroupRoleMembers = canAgentViewRoleMembers($requestingAgentID, $groupID, '');

	$memberResults = array();
	while($memberInfo = mysql_fetch_assoc($groupmemberResults)) {
		$agentID = $memberInfo['AgentID'];
		$sql = sprintf("SELECT
							BIT_OR(%2\$s.Powers) AS AgentPowers,
							(BIT_OR(%2\$s.Powers) & %3\$d) AS MemberVisible
						FROM
							%1\$s JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.RoleID = %2\$s.RoleID)
						WHERE
							%1\$s.GroupID = '%4\$s'
						AND
							%1\$s.AgentID = '%5\$s'",
				$tbl_osgrouprolemembership,
				$tbl_osrole,
				$roleMembersVisibleBit,
				$groupID,
				$agentID);

		$memberPowersResult = mysql_query($sql,$groupDBCon);
		if(!$memberPowersResult) {
			return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
		}

		if (mysql_num_rows($memberPowersResult) == 0) {
			if($canViewAllGroupRoleMembers || ($memberResults[$agentID] == $requestingAgent)) {
				$memberResults[$agentID] = array_merge($memberInfo,array('AgentPowers' => 0));
			} else {
				// if can't view all group role members and there is no Member Visible bit, then don't return this member's info
				unset($memberResults[$agentID]);
			}
		} else {
			$memberPowersInfo = mysql_fetch_assoc($memberPowersResult);
			if($memberPowersInfo['MemberVisible'] || $canViewAllGroupRoleMembers) {
				$memberResults[$agentID] = array_merge($memberInfo, $memberPowersInfo);
			} else {
				// if can't view all group role members and there is no Member Visible bit, then don't return this member's info
				unset($memberResults[$agentID]);
			}
		}
	}

	if (count($memberResults) == 0) {
		return array('succeed' => 'false', 'error' => 'No Visible Group Members found', 'params' => var_export($params, TRUE), 'sql' => $sql, 'line' => __LINE__);
	}

	return $memberResults;
}


function getAgentActiveMembership($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$agentID = $params['AgentID'];

	$sql = sprintf("SELECT
						%2\$s.GroupID,
						%2\$s.Name as GroupName,
						%2\$s.Charter,
						%2\$s.InsigniaID,
						%2\$s.FounderID,
						%2\$s.MembershipFee,
						%2\$s.OpenEnrollment,
						%2\$s.ShowInList,
						%2\$s.AllowPublish,
						%2\$s.MaturePublish,
						%3\$s.Contribution,
						%3\$s.ListInProfile,
						%3\$s.AcceptNotices,
						%3\$s.SelectedRoleID,
						%4\$s.Title,
						%1\$s.ActiveGroupID
					FROM
						%1\$s
							JOIN %2\$s ON (%2\$s.GroupID = %1\$s.ActiveGroupID)
							JOIN %3\$s ON (%2\$s.GroupID = %3\$s.GroupID AND %1\$s.AgentID = %3\$s.AgentID)
							JOIN %4\$s ON (%3\$s.SelectedRoleID = %4\$s.RoleID AND %3\$s.GroupID = %4\$s.GroupID)
					WHERE
						%1\$s.AgentID = '$agentID'",
			$tbl_osagent,
			$tbl_osgroup,
			$tbl_osgroupmembership,
			$tbl_osrole);

	$groupmembershipResult = mysql_query($sql,$groupDBCon);
	if(!$groupmembershipResult) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}
	if(mysql_num_rows($groupmembershipResult) == 0) {
		return array('succeed' => 'false', 'error' => 'No Active Group Specified', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}
	$groupMembershipInfo = mysql_fetch_assoc($groupmembershipResult);

	$groupID = $groupMembershipInfo['GroupID'];
	$sql = sprintf("SELECT
						BIT_OR(%2\$s.Powers) AS GroupPowers
					FROM
						%1\$s JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.RoleID = %2\$s.RoleID)
					WHERE
						%1\$s.GroupID = '%3\$s'
					AND
						%1\$s.AgentID = '%4\$s'",
			$tbl_osgrouprolemembership,
			$tbl_osrole,
			$groupID,
			$agentID);
	$groupPowersResult = mysql_query($sql,$groupDBCon);
	if(!$groupPowersResult) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}
	$groupPowersInfo = mysql_fetch_assoc($groupPowersResult);

	return array_merge($groupMembershipInfo,$groupPowersInfo);
}

function getAgentRoles($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon;

	$agentID = $params['AgentID'];

	$sql = sprintf("SELECT
						%3\$s.RoleID,
						%3\$s.GroupID,
						%3\$s.Title,
						%3\$s.Name,
						%3\$s.Description,
						%3\$s.Powers,
						CASE WHEN %1\$s.SelectedRoleID = %3\$s.RoleID THEN 1 ELSE 0 END AS Selected
					FROM
						%1\$s
							JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.AgentID = %2\$s.AgentID)
							JOIN %3\$s ON (%2\$s.RoleID = %3\$s.RoleID AND %2\$s.GroupID = %3\$s.GroupID)
							LEFT JOIN %4\$s ON (%4\$s.AgentID = %1\$s.AgentID)
					WHERE
						%1\$s.AgentID = '%5\$s'",
			$tbl_osgroupmembership,
			$tbl_osgrouprolemembership,
			$tbl_osrole,
			$tbl_osagent,
			$agentID);

	if(isset($params['GroupID'])) {
		$groupID = $params['GroupID'];
		$sql .= sprintf(" AND %s.GroupID = '%s'",$tbl_osgroupmembership,$groupID);
	}

	$roleResults = mysql_query($sql,$groupDBCon);
	if(!$roleResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($roleResults) == 0) {
		return array('succeed' => 'false', 'error' => 'No roles found for agent '.$agentID, 'params' => var_export($params, TRUE), 'sql' => $sql, 'line' => __LINE__);
	}

	$roles = array();
	while($role = mysql_fetch_assoc($roleResults)) {
		$ID = $role['GroupID'].$role['RoleID'];
		$roles[$ID] = $role;
	}

	return $roles;
}

function getGroupRoles($params) {
	if(is_array($error = secureRequest($params, FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$groupID = $params['GroupID'];

	$sql = sprintf("SELECT
						%1\$s.RoleID,
						%1\$s.Name,
						%1\$s.Title,
						%1\$s.Description,
						%1\$s.Powers,
						COUNT(%2\$s.AgentID) as Members
					FROM
						%1\$s LEFT JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.RoleID = %2\$s.RoleID)
					WHERE
						%1\$s.GroupID = '%3\$s'
					GROUP BY
						%1\$s.RoleID, %1\$s.Name, %1\$s.Title, %1\$s.Description, %1\$s.Powers",
			$tbl_osrole,
			$tbl_osgrouprolemembership,
			$groupID);

	$roleResults = mysql_query($sql,$groupDBCon);
	if(!$roleResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($roleResults) == 0) {
		return array('succeed' => 'false', 'error' => 'No roles found for group', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$roles = array();
	while($role = mysql_fetch_assoc($roleResults)) {
		$RoleID = $role['RoleID'];
		$roles[$RoleID] = $role;
	}

	return $roles;
}

function getGroupRoleMembers($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon,$groupPowers;

	$groupID			= (isset($params['GroupID']))			? $params['GroupID']:"";
	$requestingAgentID	= (isset($params['requestingAgentID']))	? $params['requestingAgentID']:"";

	$roleMembersVisibleBit = $groupPowers['RoleMembersVisible'];
	$canViewAllGroupRoleMembers = canAgentViewRoleMembers($requestingAgentID, $groupID,'');

	$sql = sprintf("SELECT
						%1\$s.RoleID,
						%2\$s.AgentID,
						(%1\$s.Powers & %3\$d) AS MemberVisible
					FROM
						%1\$s JOIN %2\$s ON (%1\$s.GroupID = %2\$s.GroupID AND %1\$s.RoleID = %2\$s.RoleID)
					WHERE
						%1\$s.GroupID = '%4\$s'",
			$tbl_osrole,
			$tbl_osgrouprolemembership,
			$roleMembersVisibleBit,
			$groupID);
	debugzeile("\nsql in line ".__LINE__.":\n\n".var_export($sql,TRUE));

	$memberResults = mysql_query($sql,$groupDBCon);
	if(!$memberResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($memberResults) == 0) {
		return array('succeed' => 'false', 'error' => 'No role memberships found for group', 'params' => var_export($params, TRUE), 'sql' => $sql, 'line' => __LINE__);
	}

	$members = array();
	while($member = mysql_fetch_assoc($memberResults)) {
		if($canViewAllGroupRoleMembers || $member['MemberVisible'] || ($member['AgentID'] == $requestingAgent)) {
			$Key = $member['AgentID'].$member['RoleID'];
			$members[$Key ] = $member;
		}
	}

	if(count($members) == 0) {
		return array('succeed' => 'false', 'error' => 'No rolememberships visible for group', 'params' => var_export($params, TRUE), 'sql' => $sql, 'line' => __LINE__);
	}

	return $members;
}

function setAgentGroupInfo($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon;

	if (isset($params['AgentID'])) {
		$agentID = $params['AgentID'];
	} else {
		$agentID = "";
	}
	if (isset($params['GroupID'])) {
		$groupID = $params['GroupID'];
	} else {
		$groupID = "";
	}
	if (isset($params['SelectedRoleID'])) {
		$roleID  = $params['SelectedRoleID'];
	} else {
		$roleID = "";
	}
	if (isset($params['AcceptNotices'])) {
		$acceptNotices  = $params['AcceptNotices'];
	} else {
		$acceptNotices = 1;
	}
	if (isset($params['ListInProfile'])) {
		$listInProfile  = $params['ListInProfile'];
	} else {
		$listInProfile = 0;
	}

	if(isset($requestingAgent) && ($requestingAgent != $uuidZero) && ($requestingAgent != $agentID)) {
		return array('error' => "Agent can only change their own group info", 'params' => var_export($params, TRUE));
	}

	$sql = sprintf("UPDATE %s
					SET
						AgentID = '%s'",$tbl_osgroupmembership,$agentID);
	if(isset($params['SelectedRoleID'])) {
		$sql .= sprintf(", SelectedRoleID = '%s'",$roleID);
	}
	if(isset($params['AcceptNotices'])) {
		$sql .= sprintf(", AcceptNotices = '%d'",$acceptNotices);
	}
	if(isset($params['ListInProfile'])) {
		$sql .= sprintf(", ListInProfile = '%d'",$listInProfile);
	}
	$sql .= sprintf(" WHERE %1\$s.GroupID = '%2\$s' AND %1\$s.AgentID = '%3\$s'",
				$tbl_osgroupmembership,
				$groupID,
				$agentID);

	$memberResults = mysql_query($sql,$groupDBCon);
	if(!$memberResults) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array('success'=> 'true');
}

function getGroupNotices($params) {
	if(is_array($error = secureRequest($params, FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$groupID = $params['GroupID'];

	$sql = sprintf("SELECT GroupID, NoticeID, Timestamp, FromName, Subject, Message, BinaryBucket FROM %s WHERE GroupID = '%s'",
				$tbl_osgroupnotice,
				$groupID);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($results) == 0) {
		return array('succeed' => 'false', 'error' => 'No Notices', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$notices = array();
	while($notice = mysql_fetch_assoc($results)) {
		$NoticeID = $notice['NoticeID'];
		$notices[$NoticeID] = $notice;
	}

	return $notices;
}

function getGroupNotice($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$noticeID = $params['NoticeID'];

	$sql = sprintf("SELECT GroupID, NoticeID, Timestamp, FromName, Subject, Message, BinaryBucket FROM %s WHERE NoticeID = '%s'",
				$tbl_osgroupnotice,
				$noticeID);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($results) == 0) {
		return array('succeed' => 'false', 'error' => 'Group Notice Not Found', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}

	$retval = mysql_fetch_assoc($results);
	$retval['Subject'] = utf8_decode($retval['Subject']);
	$retval['Message'] = utf8_decode($retval['Message']);
	return $retval;
}

function addGroupNotice($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$groupID		= $params['GroupID'];
	$noticeID		= $params['NoticeID'];
	$fromName		= mysqlsafestring($params['FromName']);
	$subject		= mysqlsafestring(utf8_encode($params['Subject']));
	$binaryBucket	= $params['BinaryBucket'];
	$message		= mysqlsafestring(utf8_encode($params['Message']));
	$timeStamp		= $params['TimeStamp'];

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['SendNotices']))) {
		return $error;
	}

	$sql = sprintf("INSERT INTO %s
						(GroupID, NoticeID, Timestamp, FromName, Subject, Message, BinaryBucket)
					VALUES
						('%s', '%s', '%d', '%s', '%s', '%s', '%s')",
			$tbl_osgroupnotice,
			$groupID,
			$noticeID,
			$timeStamp,
			$fromName,
			$subject,
			$message,
			$binaryBucket);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array('success' => 'true');
}


function addAgentToGroupInvite($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	$inviteID	= $params['InviteID'];
	$groupID	= $params['GroupID'];
	$roleID		= $params['RoleID'];
	$agentID	= $params['AgentID'];

	if(is_array($error = checkGroupPermission($groupID,$groupPowers['Invite']))) {
		return $error;
	}

	// Remove any existing invites for this agent to this group
	$sql = sprintf("DELETE FROM %s WHERE AgentID = '%s' AND GroupID = '%s'",
				$tbl_osgroupinvite,
				$agentID,
				$groupID);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	// Add new invite for this agent to this group for the specifide role
	$sql = sprintf("INSERT INTO %s
						(InviteID, GroupID, RoleID, AgentID)
					VALUES
						('%s', '%s', '%s', '%s')",
			$tbl_osgroupinvite,
			$inviteID,
			$groupID,
			$roleID,
			$agentID);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array('success' => 'true');
}

function getAgentToGroupInvite($params) {
	if(is_array($error = secureRequest($params,FALSE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon;

	$inviteID = $params['InviteID'];

	$sql = sprintf("SELECT GroupID, RoleID, AgentID FROM %s WHERE InviteID = '%s'",$tbl_osgroupinvite,$inviteID);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	if(mysql_num_rows($results) == 1) {
		$inviteInfo	= mysql_fetch_assoc($results);
		$groupID	= $inviteInfo['GroupID'];
		$roleID		= $inviteInfo['RoleID'];
		$agentID	= $inviteInfo['AgentID'];

		return array('success' => 'true', 'GroupID'=>$groupID, 'RoleID'=>$roleID, 'AgentID'=>$agentID);
	} else {
		return array('succeed' => 'false', 'error' => 'Invitation not found', 'params' => var_export($params, TRUE), 'sql' => $sql);
	}
}

function removeAgentToGroupInvite($params) {
	if(is_array($error = secureRequest($params,TRUE))) {
		return $error;
	}

	global $tbl_osgroup,$tbl_osagent,$tbl_osgroupinvite,$tbl_osgroupmembership,$tbl_osgroupnotice,$tbl_osgrouprolemembership,$tbl_osrole;
	global $groupEnforceGroupPerms,$requestingAgent,$uuidZero,$groupDBCon;

	$inviteID = $params['InviteID'];

	$sql = sprintf("DELETE FROM %s WHERE InviteID = '%s'",$tbl_osgroupinvite,$inviteID);

	$results = mysql_query($sql,$groupDBCon);
	if(!$results) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB: " . mysql_error(), 'params' => var_export($params, TRUE));
	}

	return array('success' => 'true');
}

function secureRequest($parameter, $write = FALSE) {
	global 	$groupWriteKey,$groupReadKey,$verifiedReadKey,$verifiedWriteKey,$groupRequireAgentAuthForWrite,$requestingAgent;
	global  $overrideAgentUserService;

	// for testing
	if($_SERVER["REMOTE_ADDR"] == "85.125.48.154") return TRUE;

	// Cache this for access by other security functions
	if(!isset($parameter['RequestingAgentID'])) return array('error' => "Invalid RequestingAgentID specified", 'params' => var_export($parameter, TRUE));
	$requestingAgent = $parameter['RequestingAgentID'];


	if(isset($groupReadKey) && ($groupReadKey != '') && (!isset($verifiedReadKey) || ($verifiedReadKey !== TRUE))) {
		if(!isset($parameter['ReadKey']) || ($parameter['ReadKey'] != $groupReadKey )) {
			return array('error' => "Invalid (or No) read key specified", 'params' => var_export($parameter, TRUE));
		} else {
			$verifiedReadKey = TRUE;
		}
	}

	if(($write == TRUE) && isset($groupWriteKey) && ($groupWriteKey != '') && (!isset($verifiedWriteKey) || ($verifiedWriteKey !== TRUE))) {
		if(!isset($parameter['WriteKey']) || ($parameter['WriteKey'] != $groupWriteKey )) {
			return array('error' => "Invalid (or No) write key specified", 'params' => var_export($parameter, TRUE));
		} else {
			$verifiedWriteKey = TRUE;
		}
	}

	if(($write == TRUE) && isset($groupRequireAgentAuthForWrite) && ($groupRequireAgentAuthForWrite == TRUE)) {
		// Note: my brain can't do boolean logic this morning, so just putting this here instead of integrating with line above.
		// If the write key has already been verified for this request, don't check it again.  This comes into play with methods that call other methods, such as CreateGroup() which calls Addrole()
		if(isset($verifiedWriteKey) && ($verifiedWriteKey !== TRUE)) {
			return TRUE;
		}

		if(!isset($parameter['RequestingAgentID']) || !isset($parameter['RequestingAgentUserService']) || !isset($parameter['RequestingSessionID'])) {
			return array('error' => "Requesting AgentID and SessionID must be specified", 'params' => var_export($parameter, TRUE));
		}

		// NOTE: an AgentID and SessionID of $uuidZero will likely be a region making a request, that is not tied to a specific agent making the request.

		$UserService = $parameter['RequestingAgentUserService'];
		if(isset($overrideAgentUserService) && ($overrideAgentUserService != "")) {
			$UserService = $overrideAgentUserService;
		}

		$client = new xmlrpc_client($UserService);
		$client->return_type = 'phpvals';

		$verifyParams = new xmlrpcval(array('avatar_uuid' => new xmlrpcval($parameter['RequestingAgentID'], 'string'),
										    'session_id'  => new xmlrpcval($parameter['RequestingSessionID'], 'string')),
									  'struct');

		$message = new xmlrpcmsg("check_auth_session", array($verifyParams));
		$resp = $client->send($message, 5);
		if ($resp->faultCode()) {
			return array('error' => "Error validating AgentID and SessionID",'xmlrpcerror'=> $resp->faultString(),'params' => var_export($parameter, TRUE));
		}

		$verifyReturn = $resp->value();

		if(!isset($verifyReturn['auth_session']) || ($verifyReturn['auth_session'] != 'TRUE')) {
			return array('error' => "UserService.check_auth_session() did not return TRUE", 'userservice' => var_export($verifyReturn, TRUE),'params' => var_export($parameter, TRUE));
		}
	}
	return TRUE;
}

function checkGroupPermission($GroupID,$Permission) {
	global $groupEnforceGroupPerms, $requestingAgent, $uuidZero, $groupDBCon, $groupPowers;

	if(!isset($Permission) || ($Permission == 0)) {
		return array('error' => 'No Permission value specified for checkGroupPermission','Permission' => $Permission);
	}

	// If it isn't set to true, then always return true, otherwise verify they have perms
	if(!isset($groupEnforceGroupPerms) || ($groupEnforceGroupPerms != TRUE)) {
		return true;
	}

	if(!isset($requestingAgent) || ($requestingAgent == $uuidZero)) {
		return array('error' => 'Requesting agent was either not specified or not validated.','requestingAgent' => $requestingAgent);
	}

	$params = array('AgentID' => $requestingAgent, 'GroupID' => $GroupID);
	$reqAgentMembership = getAgentGroupMembership($params);

	if(isset($reqAgentMembership['error'] )) {
		return array('error' => 'Could not get agent membership for group','params' => var_export($params, TRUE),'nestederror' => $reqAgentMembership['error']);
	}

	// Worlds ugliest bitwise operation, EVER
	$PermMask	= $reqAgentMembership['GroupPowers'];
	$PermValue	= $Permission;

	global $groupDBCon;
	$sql = sprintf("SELECT %d & %d AS Allowed",$PermMask,$PermValue);
	$result = mysql_query($sql,$groupDBCon);
	if(!$result) {
		return array('error' => "Line ".__LINE__.": Could not successfully run query ($sql) from DB",'params' => mysql_error());
	}
	$PermMasked = mysql_result($results,0);

	if($PermMasked != $Permission) {
		$permNames = array_flip($groupPowers);
		return array('error' => 'Agent does not have group power to ' . $Permission .'('.$permNames[$Permission].')','PermMasked' => $PermMasked,'params' => var_export($params, TRUE),'permBitMaskSql' => $sql,'Permission' => $Permission);
	}

	return TRUE;
}
?>