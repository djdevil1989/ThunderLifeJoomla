<?php
/***********************************************************************

 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *

xmlrpc functions for profile handling

 * @component jOpenSim (Communication Interface with the OpenSim Server)
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html

***********************************************************************/

$xmlrpcserver = new jxmlrpc_server(array(
		// Profile Functions
			"avatar_properties_request"	=> array("function" => "avatar_properties_request"),
			"avatar_properties_update"	=> array("function" => "avatar_properties_update"),
			"avatar_interests_update"	=> array("function" => "avatar_interests_update"),
			"avatarnotesrequest"		=> array("function" => "avatarnotesrequest"),
			"avatar_notes_update"		=> array("function" => "avatar_notes_update"),
			"avatarpicksrequest"		=> array("function" => "avatarpicksrequest"),
			"pickinforequest"			=> array("function" => "pickinforequest"),
			"picks_update"				=> array("function" => "picks_update"),
			"picks_delete"				=> array("function" => "picks_delete"),
			"avatarclassifiedsrequest"	=> array("function" => "avatarclassifiedsrequest"),
			"classifiedinforequest"		=> array("function" => "classifiedinforequest"),
			"classified_update"			=> array("function" => "classified_update"),
			"classified_delete"			=> array("function" => "classified_delete"),
			"user_preferences_request"	=> array("function" => "user_preferences_request"),
			"clientInfo"				=> array("function" => "clientInfo"),
			"user_preferences_update"	=> array("function" => "user_preferences_update"),
	), false);


function addon_disabled($parameter) {
	$retval['success'] = FALSE;
	$retval['error'] = "Profile Addon disabled in com_opensim";
	$retval['params'] = $parameter;
	return $retval;
}

function user_preferences_request($parameter) {
	global $opensim,$debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") {
		simpledebugzeile(__FUNCION__.": ".var_export($parameter,TRUE));
	}
	$uuid 		= $parameter['avatar_id'];
	$query = sprintf("SELECT im2email, visible FROM #__opensim_usersettings WHERE uuid = '%s'",$uuid);
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
	$num_rows = $db->getNumRows();
	if($num_rows == 0) {
		$query = sprintf("INSERT INTO #__opensim_usersettings (uuid,im2email,visible) VALUES ('%s',0,0)",$uuid);
		$db->setQuery($query);
		$db->query();
	} else {
		$usersettings = $db->loadAssoc();
	}
	$user['imviaemail'] = (isset($usersettings['im2email']) && $usersettings['im2email'] == 1) ? "true":"false";
	$user['visible'] = (isset($usersettings['visible']) && $usersettings['visible'] == 1) ? "true":"false";

	$usersettings2 = $opensim->getUserData($uuid);

	$data[] = array('imviaemail' => $user['imviaemail'],
					'visible' 	 => $user['visible'],
					'email' 	 => $usersettings2['email'],
					'userFlags'	 => 65535);

	$retval['success'] = TRUE;
	$retval['errorMessage']	= "";
	$retval['data'] = $data;
	return $retval;
}

function user_preferences_update($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$uuid = $parameter['avatar_id'];
	$im2email = (strtolower($parameter['imViaEmail']) == "true") ? "1":"0";
	$visible = (strtolower($parameter['visible']) == "true") ? "1":"0";
	$query = sprintf("UPDATE #__opensim_usersettings SET im2email = '%d', visible = '%d' WHERE uuid = '%s'",$im2email,$visible,$uuid);
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
	$retval['success'] = TRUE;
	$retval['errorMessage']	= "";
	return $retval;
}

function avatar_properties_request($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") {
		simpledebugzeile(__FUNCTION__.": ".var_export($parameter,TRUE));
	}
	$avatar_id = $parameter['avatar_id'];
	$query = sprintf("SELECT
						partner 		AS Partner,
						url				AS ProfileUrl,
						wantmask		AS wantmask,
						wanttext		AS wanttext,
						skillsmask		AS skillsmask,
						skillstext		AS skillstext,
						languages		AS languages,
						firstLifeImage	AS FirstLifeImage,
						firstLifeText	AS FirstLifeAboutText,
						image			AS Image,
						aboutText		AS AboutText
					FROM
						#__opensim_userprofile
					WHERE
						avatar_id = '%s'",
				mysql_escape_string($avatar_id));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$profile = $db->loadAssocList();
	$retval['success'] = TRUE;
	if(count($profile) == 1) {
		// decode first all during storing encoded fields
		$retval['data'][0]['Partner']				= $profile[0]['Partner'];
		$retval['data'][0]['ProfileUrl']			= utf8_decode($profile[0]['ProfileUrl']);
		$retval['data'][0]['wantmask']				= $profile[0]['wantmask'];
		$retval['data'][0]['wanttext']				= utf8_decode($profile[0]['wanttext']);
		$retval['data'][0]['skillsmask']			= $profile[0]['skillsmask'];
		$retval['data'][0]['skillstext']			= utf8_decode($profile[0]['skillstext']);
		$retval['data'][0]['languages']				= utf8_decode($profile[0]['languages']);
		$retval['data'][0]['FirstLifeImage']		= $profile[0]['FirstLifeImage'];
		$retval['data'][0]['FirstLifeAboutText']	= utf8_decode($profile[0]['FirstLifeAboutText']);
		$retval['data'][0]['Image']					= $profile[0]['Image'];
		$retval['data'][0]['AboutText']				= utf8_decode($profile[0]['AboutText']);
	} else {
		$retval['data'][0]['Partner']				= null;
		$retval['data'][0]['ProfileUrl']			= null;
		$retval['data'][0]['wantmask']				= 0;
		$retval['data'][0]['wanttext']				= "";
		$retval['data'][0]['skillsmask']			= 0;
		$retval['data'][0]['skillstext']			= "";
		$retval['data'][0]['languages']				= "";
		$retval['data'][0]['FirstLifeImage']		= null;
		$retval['data'][0]['FirstLifeAboutText']	= "";
		$retval['data'][0]['Image']					= null;
		$retval['data'][0]['AboutText'] 			= JText::_('NOPROFILEAVAILABLE');
	}
	$retval['data'][0]['userFlags'] = 65535;
	return $retval;
}

function avatar_properties_update($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$query = sprintf("INSERT INTO #__opensim_userprofile
							(avatar_id,aboutText,firstLifeImage,image,url,firstLifeText)
						VALUES
							('%1\$s','%2\$s','%3\$s','%4\$s','%5\$s','%6\$s')
						ON DUPLICATE KEY UPDATE
							aboutText = '%2\$s',
							firstLifeImage = '%3\$s',
							image = '%4\$s',
							url = '%5\$s',
							firstLifeText = '%6\$s'",
				mysql_escape_string($parameter['avatar_id']),
				mysql_escape_string(utf8_encode($parameter['AboutText'])),
				mysql_escape_string($parameter['FirstLifeImage']),
				mysql_escape_string($parameter['Image']),
				mysql_escape_string(utf8_encode($parameter['ProfileUrl'])),
				mysql_escape_string(utf8_encode($parameter['FirstLifeAboutText'])));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATEPROFILE');
	}
	return $retval;
}

function avatar_interests_update($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$db =& JFactory::getDBO();
	$query = sprintf("INSERT INTO #__opensim_userprofile
								(avatar_id,skillstext,languages,wantmask,skillsmask,wanttext)
							VALUES
								('%1\$s','%2\$s','%3\$s','%4\$s','%5\$s','%6\$s')
							ON DUPLICATE KEY UPDATE
								skillstext	= '%2\$s',
								languages	= '%3\$s',
								wantmask	= '%4\$s',
								skillsmask	= '%5\$s',
								wanttext	= '%6\$s'",
					mysql_escape_string($parameter['avatar_id']),
					mysql_escape_string(utf8_encode($parameter['skillstext'])),
					mysql_escape_string(utf8_encode($parameter['languages'])),
					mysql_escape_string($parameter['wantmask']),
					mysql_escape_string($parameter['skillsmask']),
					mysql_escape_string(utf8_encode($parameter['wanttext'])));
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATEPROFILE');
	}
	return $retval;
}

function avatarnotesrequest($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$avatar_id = $parameter['avatar_id'];
	$target_uid = $parameter['uuid'];
	$query = sprintf("SELECT * FROM #__opensim_usernotes WHERE avatar_id = '%s' AND target_id = '%s'",
						mysql_escape_string($avatar_id),
						mysql_escape_string($target_uid));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$notes = $db->loadAssocList();
	if(count($notes) == 0) {
		$retval['data'][0]['notes'] = null;
	} else {
		$retval['data'][0]['notes'] 	= utf8_decode($notes[0]['notes']);
	}
	$retval['data'][0]['targetid']	= $target_uid;
	$retval['success'] = TRUE;
	$retval['errorMessage'] = '';
//	debugzeile("\avatarnotesrequest return -> ".var_export($retval,TRUE));
	return $retval;
}

function avatar_notes_update($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$avatar_id = $parameter['avatar_id'];
	$target_uid = $parameter['target_id'];
	$notes = utf8_encode($parameter['notes']);
	$db =& JFactory::getDBO();
	$query = sprintf("INSERT INTO #__opensim_usernotes (avatar_id,target_id,notes) VALUES ('%1\$s','%2\$s','%3\$s')
								ON DUPLICATE KEY UPDATE notes = '%3\$s'",
						mysql_escape_string($avatar_id),
						mysql_escape_string($target_uid),
						mysql_escape_string($notes));
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATENOTES');
	}
	return $retval;
}

function avatarpicksrequest($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") {
		simpledebugzeile(__FUNCTION__.": ".var_export($parameter,TRUE));
	}
	$db =& JFactory::getDBO();
	$query = sprintf("SELECT * FROM #__opensim_userpicks WHERE creatoruuid = '%s'",mysql_escape_string($parameter['uuid']));
	$db->setQuery($query);
	$picks = $db->loadAssocList();
	$data = array();
	foreach($picks AS $pick) {
		$name = $pick['name'];
		$data[] = array('pickid' => $pick['pickuuid'],
						'name' 	 => $name );
	}
	$retval = array('success'		=> True,
					'errorMessage'	=> '',
					'data'			=> $data);
	return $retval;
}

function pickinforequest($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$db =& JFactory::getDBO();
	$query = sprintf("SELECT * FROM #__opensim_userpicks WHERE creatoruuid = '%s' AND pickuuid = '%s'",
							mysql_escape_string($parameter['avatar_id']),
							mysql_escape_string($parameter['pick_id']));
	$db->setQuery($query);
	$pick = $db->loadAssocList();
	$returnpick = $pick[0];
	$returnpick['description'] = $returnpick['description'];
	$returnpick['name'] = $returnpick['name'];
	$data[] = array(
			'pickuuid' 		=> $returnpick['pickuuid'],
			'creatoruuid' 	=> $returnpick['creatoruuid'],
			'toppick' 		=> $returnpick['toppick'],
			'parceluuid' 	=> $returnpick['parceluuid'],
			'name' 			=> utf8_decode($returnpick['name']),
			'description' 	=> utf8_decode($returnpick['description']),
			'snapshotuuid' 	=> $returnpick['snapshotuuid'],
			'user' 			=> utf8_decode($returnpick['user']),
			'originalname' 	=> utf8_decode($returnpick['originalname']),
			'simname' 		=> utf8_decode($returnpick['simname']),
			'posglobal' 	=> $returnpick['posglobal'],
			'sortorder'		=> $returnpick['sortorder'],
			'enabled' 		=> $returnpick['enabled']);
	$retval = array('success'		=> True,
					'errorMessage'	=> '',
					'data'			=> $data);
	return $retval;
}

function picks_update($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	// debugzeile(utf8_encode("Parameter f�r $method:\n").var_export($parameter,TRUE));

	$pickuuid		= $parameter['pick_id'];
	$creator		= $parameter['creator_id'];
	$toppick		= $parameter['top_pick'];
	$parceluuid		= $parameter['parcel_uuid'];
	$name			= $parameter['name'];
	$description	= $parameter['desc'];
	$snapshotuuid	= $parameter['snapshot_id']; 
	$user			= $parameter['user'];
	$original		= $parameter['name'];
	$simname		= $parameter['sim_name'];
	$posglobal		= $parameter['pos_global'];
	$sortorder		= $parameter['sort_order'];
	$enabled		= $parameter['enabled'];

	if ($parceluuid=='')  $parceluuid  = '00000000-0000-0000-0000-0000000000000';
	if ($description=='') $description = JText::_('NO_DESCRIPTION');
	if ($user=='') 		  $user 	   = JText::_('UNKNOWN');
	if ($original=='') 	  $original    = JText::_('UNKNOWN');

	$query = sprintf("INSERT INTO #__opensim_userpicks
												(pickuuid,creatoruuid,toppick,parceluuid,name,description,snapshotuuid,user,originalname,simname,posglobal,sortorder,enabled)
											VALUES
												('%1\$s','%2\$s','%3\$s','%4\$s','%5\$s','%6\$s','%7\$s','%8\$s','%9\$s','%10\$s','%11\$s','%12\$s','%13\$s')
											ON DUPLICATE KEY UPDATE
												creatoruuid		= '%2\$s',
												toppick			= '%3\$s',
												parceluuid		= '%4\$s',
												name			= '%5\$s',
												description		= '%6\$s',
												snapshotuuid	= '%7\$s',
												user			= '%8\$s',
												simname			= '%10\$s',
												posglobal		= '%11\$s',
												sortorder		= '%12\$s',
												enabled			= '%13\$s'",
							mysql_escape_string($pickuuid),
							mysql_escape_string($creator),
							mysql_escape_string($toppick),
							mysql_escape_string($parceluuid),
							mysql_escape_string($name),
							mysql_escape_string(utf8_encode($description)),
							mysql_escape_string($snapshotuuid),
							mysql_escape_string($user),
							mysql_escape_string($original),
							mysql_escape_string($simname),
							mysql_escape_string($posglobal),
							mysql_escape_string($sortorder),
							mysql_escape_string($enabled));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATEPICKS');
	}
	return $retval;
}

function picks_delete($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$pickUid = $parameter['pick_id'];
	$query = sprintf("DELETE FROM #__opensim_userpicks WHERE pickuuid = '%s'",mysql_escape_string($pickUid));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATEPICKS');
	}
	return $retval;
}

function avatarclassifiedsrequest($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$classifieds = array();
	$data = array();
	$db =& JFactory::getDBO();
	$query = sprintf("SELECT * FROM #__opensim_userclassifieds WHERE creatoruuid = '%s'",mysql_escape_string($parameter['uuid']));
	$db->setQuery($query);
	$classifieds = $db->loadAssocList();
	foreach($classifieds AS $classified) {
		$name = $classified['name'];
		$data[] = array('classifiedid' => $classified['classifieduuid'],
						'name' 	 => $name );
	}
	$retval = array('success'		=> True,
					'errorMessage'	=> '',
					'data'			=> $data);
	return $retval;
}

function classifiedinforequest($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$db =& JFactory::getDBO();
	$query = sprintf("SELECT * FROM #__opensim_userclassifieds WHERE creatoruuid = '%s' AND classifieduuid = '%s'",
							mysql_escape_string($parameter['avatar_id']),
							mysql_escape_string($parameter['classified_id']));
	$db->setQuery($query);
	$db->query();
	if($db->getNumRows() > 0) {
		$classified = $db->loadAssocList();
		$returnclassified = $classified[0];
		$returnclassified['description'] = $returnclassified['description'];
		$returnclassified['name']		 = $returnclassified['name'];
		$data[] = array(
				'classifieduuid'	=> $returnclassified['classifieduuid'],
				'creatoruuid' 		=> $returnclassified['creatoruuid'],
				'creationdate'		=> $returnclassified['creationdate'],
				'expirationdate' 	=> $returnclassified['expirationdate'],
				'category' 			=> $returnclassified['category'],
				'name' 				=> $returnclassified['name'],
				'description' 		=> $returnclassified['description'],
				'parceluuid' 		=> $returnclassified['parceluuid'],
				'parentestate' 		=> $returnclassified['parentestate'],
				'snapshotuuid' 		=> $returnclassified['snapshotuuid'],
				'simname' 			=> $returnclassified['simname'],
				'posglobal' 		=> $returnclassified['posglobal'],
				'parcelname' 		=> $returnclassified['parcelname'],
				'classifiedflags' 	=> $returnclassified['classifiedflags'],
				'priceforlisting' 	=> $returnclassified['priceforlisting']);
		$retval = array('success'		=> True,
						'errorMessage'	=> '',
						'data'			=> $data);
	} else {
		$retval = array('success'		=> True,
						'errorMessage'	=> 'Nothing found in function classifiedinforequest for avatar_id '.$parameter['avatar_id']." and classified_id ".$parameter['classified_id'],
						'data'			=> array());
	}
	return $retval;
}

function classified_update($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$classifieduuid = $parameter['classifiedUUID'];
	$creator		= $parameter['creatorUUID'];
	$category		= $parameter['category'];
	$name			= $parameter['name'];
	$description	= $parameter['description'];
	$parceluuid		= (array_key_exists("parcelUUID",$parameter)) ? $parameter['parcelUUID']:null;
	$parentestate	= $parameter['parentestate'];
	$snapshotuuid	= $parameter['snapshotUUID'];
	$simname		= $parameter['sim_name'];
	$globalpos		= $parameter['globalpos'];
	$parcelname		= (array_key_exists("parcelname",$parameter)) ? $parameter['parcelname']:null;
	$classifiedflag = $parameter['classifiedFlags'];
	$priceforlist	= $parameter['classifiedPrice'];

	if ($parcelname=='')  $parcelname  = JText::_('UNKNOWN');
	if ($parceluuid=='')  $parceluuid  = '00000000-0000-0000-0000-0000000000000';
	if ($description=='') $description = JText::_('NO_DESCRIPTION');

	if ($classifiedflag==2) {
		$creationdate   = time();
		$expirationdate = time() + (7 * 24 * 60 * 60);
	} else {
		$creationdate   = time();
		$expirationdate = time() + (365 * 24 * 60 * 60);
	}

	$query = sprintf("INSERT INTO #__opensim_userclassifieds
											(classifieduuid,creatoruuid,creationdate,expirationdate,category,name,description,parceluuid,parentestate,snapshotuuid,simname,posglobal,parcelname,classifiedflags,priceforlisting)
										VALUES
											('%1\$s','%2\$s','%3\$d','%4\$d','%5\$s','%6\$s','%7\$s','%8\$s','%9\$d','%10\$s','%11\$s','%12\$s','%13\$s','%14\$d','%15\$d')
										ON DUPLICATE KEY UPDATE
											creatoruuid		= '%2\$s',
											creationdate	= '%3\$d',
											expirationdate	= '%4\$d',
											category		= '%5\$s',
											name			= '%6\$s',
											description		= '%7\$s',
											parceluuid		= '%8\$s',
											parentestate	= '%9\$d',
											snapshotuuid	= '%10\$s',
											simname			= '%11\$s',
											posglobal		= '%12\$s',
											parcelname		= '%13\$s',
											classifiedflags	= '%14\$d',
											priceforlisting	= '%15\$d'",
							mysql_escape_string($classifieduuid),
							mysql_escape_string($creator),
							mysql_escape_string($creationdate),
							mysql_escape_string($expirationdate),
							mysql_escape_string($category),
							mysql_escape_string(utf8_encode($name)),
							mysql_escape_string(utf8_encode($description)),
							mysql_escape_string($parceluuid),
							mysql_escape_string($parentestate),
							mysql_escape_string($snapshotuuid),
							mysql_escape_string($simname),
							mysql_escape_string($globalpos),
							mysql_escape_string($parcelname),
							mysql_escape_string($classifiedflag),
							mysql_escape_string($priceforlist));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATECLASSIFIED');
	}
	return $retval;
}

function classified_delete($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$classifiedID = $parameter['classifiedID'];
	$query = sprintf("DELETE FROM #__opensim_userclassifieds WHERE classifieduuid = '%s'",mysql_escape_string($classifiedID));
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$result = $db->query();
	if($result) {
		$retval['success']		= TRUE;
		$retval['errorMessage']	= "";
	} else {
		$retval['success']		= FALSE;
		$retval['errorMessage']	= JText::_('ERROR_UPDATECLASSIFIED');
	}
	return $retval;
}

function clientInfo($parameter) {
	global $debug;
	if(is_array($debug) && array_key_exists("profile",$debug) && $debug['profile'] == "yes") debugzeile($parameter,"\$parameter for ".__FUNCTION__);
	$agent	= $parameter['agentName'];
	$userip	= $parameter['agentIP'];
	$uuid	= $parameter['agentID'];
	if(strstr($agent,"@") !== FALSE) {
		$lastpos	= strlen($agent) - strlen(strrchr($agent,"@"));
		$hoststring	= "http://".substr(strrchr($agent,"@"),1);
		$hostarray	= parse_url($hoststring);
		if(array_key_exists("host",$hostarray) && $hostarray['host']) {
			// den Hostnamen aus URL holen
			preg_match('@^(?:http://)?([^/]+)@i',$hostarray['host'], $treffer);
			$host = $treffer[1];
			// die letzten beiden Segmente aus Hostnamen holen
			preg_match('/[^.]+\.[^.]+$/', $host, $treffer);
			if(is_array($treffer) && count($treffer) > 0 && $treffer[0]) {
				$username	= substr($agent,0,$lastpos);
				$host		= $hostarray['host'];
			} else {
				$username	= $agent;
				$host		= "local";
			}
		} else {
			$username	= $agent;
			$host		= "local";
		}
	} else {
		$username	= $agent;
		$host		= "local";
	}
	$query = sprintf("INSERT INTO #__opensim_clientinfo (PrincipalID,userName,grid,remoteip,lastseen,`from`) VALUES ('%1\$s','%2\$s','%3\$s','%4\$s',NOW(),'P')
						ON DUPLICATE KEY UPDATE userName = '%2\$s', grid = '%3\$s', remoteip = '%4\$s', lastseen = NOW(), `from`= 'P'",
		$uuid,
		mysql_real_escape_string(trim($username)),
		$host,
		$userip);
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
}
?>