<?php
/***********************************************************************

 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *

xmlrpc functions for currency handling

 * @component jOpenSim (Communication Interface with the OpenSim Server)
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html

***********************************************************************/

define("_JOPENSIMCURRENCY",TRUE);

$xmlrpcserver = new jxmlrpc_server(array(
		// Currency Functions
			"GetBalance"			=> array("function" => "GetBalance"),
			"TransferMoney"			=> array("function" => "TransferMoney"),
			"AmountCovered"			=> array("function" => "AmountCovered"),
			"ApplyCharge"			=> array("function" => "ApplyCharge"),
			"getSettingsData"		=> array("function" => "getSettingsData"),
			"getCurrencyQuote"		=> array("function" => "getCurrencyQuote"),
			"preflightBuyLandPrep"	=> array("function" => "preflightBuyLandPrep"),
			"buyLandPrep"			=> array("function" => "buyLandPrep"),
			"clientInfo"			=> array("function" => "clientInfo"),
			"buyCurrency"			=> array("function" => "buyCurrency")
	), false);


function preflightBuyLandPrep($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter preflightBuyLandPrep");
	}

	$agentid	  = $parameter['agentId'];
	$sessionid	  = $parameter['secureSessionId'];
	$amount		  = $parameter['currencyBuy'];
	$billableArea = $parameter['billableArea'];
	$ipAddress 	  = $_SERVER['REMOTE_ADDR'];

	$confirmvalue = get_confirm_value();
	$membership_levels = array('levels' => array('id' => "00000000-0000-0000-0000-000000000000", 'description' => "some level"));
	$sysurl = "http:/"."/".$_SERVER['HTTP_HOST']."/components/com_opensim/";
	$landUse	= array('upgrade' => False, 'action' => "".$sysurl."");
	$currency   = array('estimatedCost' => convert_to_real($amount));
	$membership = array('upgrade' => False, 'action' => "".$sysurl."", 'levels' => $membership_levels);
	$retval = array('success'	=> True,
					'currency'  => $currency,
					'membership'=> $membership,
					'landUse'	=> $landUse,
					'currency'  => $currency,
					'confirm'	=> $confirmvalue);
	return $retval;
}

function buyLandPrep($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter buyLandPrep");
	}
//	$debug = var_export($parameter,TRUE);
//	debugzeile($debug,"buyLandPrep");
	return getCurrencyQuote($parameter);
}

function GetBalance($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter GetBalance");
	}
	$startbalance = getSettingsValue("startBalance");
	if($startbalance === FALSE || !$startbalance) { // we did not find any value for the startbalance of new users
		$startbalance = 0;
	}
	if(!isset($parameter['clientUUID'])) { // This parameter is obligatory
		$retval['success']	= FALSE;
		$retval['message']	= "No clientUUID provided for function GetBalance() in ".__FILE__." at line ".__LINE__;
		return $retval;
	}
	$clientSessionID		= (isset($parameter['clientSessionID']))		? $parameter['clientSessionID']:null;
	$clientSecureSessionID	= (isset($parameter['clientSecureSessionID']))	? $parameter['clientSecureSessionID']:null;
	$uuid					= $parameter['clientUUID'];
	$query					= sprintf("SELECT balance FROM #__opensim_moneybalances WHERE `user` = '%s'",$uuid);
	$db						=& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
	$num_rows 				= $db->getNumRows();
	if($num_rows == 0) {
		$query = sprintf("INSERT INTO #__opensim_moneybalances (`user`,`balance`) VALUES ('%s',0)",$uuid); // Insert a new row for $uuid
		$db->setQuery($query);
		$db->query();

		$parameter['senderID']		= getSettingsValue("bankerUID");
		$parameter['receiverID']	= $uuid;
		$parameter['amount']		= $startbalance;
		$parameter['description']	= JTEXT::_('JOPENSIM_MONEY_STARTBALANCE');
		TransferMoney($parameter);

		$balance = $startbalance;
	} else {
		$userbalance = $db->loadAssoc();
		$balance = $userbalance['balance'];
	}

	$retval['success']				 = TRUE;
	$retval['clientBalance']		 = intval($balance);
	$retval['clientSessionID']		 = $clientSessionID;
	$retval['clientUUID']			 = $uuid;
	$retval['clientSecureSessionID'] = $clientSecureSessionID;
	return $retval;
}

function TransferMoney($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter TransferMoney");
	}
	$isSender	= checkClient($parameter['senderID']);
	$isReceiver	= checkClient($parameter['receiverID']);

	if($isSender === FALSE) {
		$retval['success']	= FALSE;
		$retval['message']	= "Could not locate senderID ".$parameter['senderID'];
	} elseif($isReceiver === FALSE) {
		$retval['success']	= FALSE;
		$retval['message']	= "Could not locate receiverID ".$parameter['receiverID'];
	} else {
		$parameter['time'] = time();
		$parameter['status'] = 0;
		insertTransaction($parameter);

		setBalance($parameter['receiverID'],$parameter['amount']);
		setBalance($parameter['senderID'],-$parameter['amount']);

		$retval['success']				 = TRUE;
		$retval['clientUUID']			 = (isset($parameter['clientUUID']))			? $parameter['clientUUID']:null;
		$retval['clientSessionID']		 = (isset($parameter['clientSessionID']))		? $parameter['clientSessionID']:null;
		$retval['clientSecureSessionID'] = (isset($parameter['clientSecureSessionID']))	? $parameter['clientSecureSessionID']:null;
		$retval['objectID']		 		 = (isset($parameter['objectID']))				? $parameter['objectID']:null;
	}
	checkGridBalance("TransferMoney");
	return $retval;
}

function setBalance($uuid,$amount) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$arg_list = func_get_args();
		$debug = var_export($arg_list,TRUE);
		debugzeile($debug,"Parameter setBalance");
	}
	balanceExists($uuid); // $uuid could be a group, see if it exists and if not, create a balance line for it
	$query = sprintf("UPDATE #__opensim_moneybalances SET balance = balance + %d WHERE `user`= '%s'",$amount,$uuid);
	$db		=& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
//	checkGridBalance("setBalance");
}

function balanceExists($uuid) { // if this $uuid does not exist yet, it will create a 0 Balance for it (different to GetBalance, where $startbalance will be created)
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$arg_list = func_get_args();
		$debug = var_export($arg_list,TRUE);
		debugzeile($debug,"Parameter balanceExists");
	}
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

function checkClient($uuid) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$arg_list = func_get_args();
		$debug = var_export($arg_list,TRUE);
		debugzeile($debug,"Parameter checkClient");
	}
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

function AmountCovered($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter AmountCovered");
	}
	$uuid	= $parameter['clientUUID'];
	$amount	= $parameter['amount'];
	$query	= sprintf("SELECT balance FROM #__opensim_moneybalances WHERE `user` = '%s'",$uuid);
	$db		=& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
	$num_rows = $db->getNumRows();
	if($num_rows == 1) {
		$balance = $db->loadResult();
		if($balance >= $amount) {
			$retval['success'] = TRUE;
		} else {
			$retval['success'] = FALSE;
			$retval['message'] = "Insufficient balance for $amount!";
		}
	} else {
		$retval['success'] = FALSE;
		$retval['message'] = "Error while checking AmountCovered!";
	}
	return $retval;
}

function ApplyCharge($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter ApplyCharge");
	}
	$amount							= $parameter['amount'];
	$parameter['senderID']			= $parameter['clientUUID'];
	$parameter['receiverID']		= getSettingsValue("bankerUID");
	$parameter['time']				= time();
	switch($parameter['description']) {
		case "Asset upload":
			$parameter['transactionType']	= 1001;
		break;
		case "Group Creation":
			$parameter['transactionType']	= 1002;
		break;
		default:
			$parameter['transactionType']	= 1003;
		break;
	}
	$parameter['status']			= 0;

	if($parameter['receiverID'] === FALSE) {
		$retval['success'] = FALSE;
		$retval['message'] = "Banker Account not found";
	} elseif(AmountCovered($parameter) === FALSE) {
		$retval['success'] = FALSE;
		$retval['message'] = "Insufficient balance for $amount!"; // This should actually always have happened before already, but however...
	} else {
		insertTransaction($parameter);
		setBalance($parameter['receiverID'],$amount);
		setBalance($parameter['senderID'],-$amount);
		$retval['success'] = TRUE;
	}
	checkGridBalance("ApplyCharge");
	return $retval;
}

function getSettingsValue($field) {
	$query = sprintf("SELECT value FROM #__opensim_moneysettings WHERE field = '%s'",$field);
	$db		=& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
	$num_rows = $db->getNumRows();
	if($num_rows == 1) {
		$retval = $db->loadResult();
	} else {
		$retval = FALSE;
	}
	return $retval;
}

function getSettingsData($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter getSettingsData");
	}
	$feld		= $parameter['settingsfield'];
	$returntype	= $parameter['returntype'];
	$wert		= getSettingsValue($feld);
	if($wert === FALSE) {
		$retval['success']		= FALSE;
		$retval['message']		= "Could not determine value for ".$feld;
	} else {
		switch($returntype) {
			case "int":
				$retval['success']		= TRUE;
				$retval['settingvalue']	= intval($wert);
			break;
			case "string":
			case "char":
				$retval['success']		= TRUE;
				$retval['settingvalue']	= strval($wert);
			break;
			default:
				$retval['success']		= FALSE;
				$retval['message']		= "Unknown Type ".$returntype." for settingsfield ".$feld;
			break;
		}
	}
	return $retval;
}

function insertTransaction($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter insertTransaction");
	}
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

function groupMembershipFee($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter groupMembershipFee");
	}
	if(!isset($parameter['groupID']) || !isset($parameter['clientUUID'])) {
		$retval['success'] = FALSE;
		$retval['message'] = "No GroupID or no clientUUID provided for groupMembershipFee in ".__FILE__." at line ".__LINE__;
		return $retval;
	} elseif(!isset($parameter['groupPower']) || intval($parameter['groupPower']) == 0) {
		$retval['success'] = FALSE;
		$retval['message'] = "Missing GroupPower-Bit for groupMembershipFee in ".__FILE__." at line ".__LINE__;
		return $retval;
	}
	$groupID	= $parameter['groupID'];
	$agentID	= $parameter['clientUUID'];
	$groupfee	= sprintf("%d",$parameter['groupFee']);
	$groupPower	= $parameter['groupPower'];
	$sessionID	= (isset($parameter['sessionID'])) ? $parameter['sessionID']:null;

	balanceExists($groupID); // check if Group already has a balance line (and if not create it)
	$parameter 							= array();
	$parameter['amount']				= $groupfee;
	$parameter['senderID']				= $agentID;
	$parameter['receiverID']			= $groupID;
	$parameter['clientSessionID']		= $sessionID;
	$parameter['clientUUID']			= $agentID;
	$parameter['clientSecureSessionID']	= null;
	$parameter['description']			= "Group Enrollment Fee";
	$retval								= TransferMoney($parameter); // Transfer the groupfee from the new member to the group
	checkGridBalance("groupMembershipFee");
	if($retval['success'] === FALSE) {
		return $retval; // something went wrong?
	} else { // everything ok, we should check if group pays dividend now
		$parameter['groupID']		= $groupID;
		$parameter['groupPower']	= $groupPower;
		$retval = groupDividend($parameter);
		return $retval;
	}
}

function groupDividend($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter groupDividend");
	}
	if(!isset($parameter['groupID'])) {
		$retval['success'] = FALSE;
		$retval['message'] = "No GroupID provided for groupDividend in ".__FILE__." at line ".__LINE__;
		return $retval;
	} elseif(!isset($parameter['groupPower']) || intval($parameter['groupPower']) == 0) {
		$retval['success'] = FALSE;
		$retval['message'] = "Missing GroupPower-Bit for groupMembershipFee in ".__FILE__." at line ".__LINE__;
		return $retval;
	}

	$groupMinDividend	= getSettingsValue('groupMinDividend');
	$groupMinDividend	= intval($groupMinDividend);
	if($groupMinDividend === FALSE || intval($groupMinDividend) == 0) { // nothing set for groupMinDividend, we dont pay anything
		$retval['success'] = FALSE;
		$retval['message'] = "Missing value for groupMinDividend in ".__FILE__." at line ".__LINE__;
		return $retval;
	}
	$groupID			= $parameter['groupID'];
	$query				= sprintf("SELECT balance FROM #__opensim_moneybalances WHERE #__opensim_moneybalances.`user` = '%s'",$groupID);
	$db					=& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
	$num_rows			= $db->getNumRows();
	$groupBalance		= $db->loadResult();

	if($num_rows == 0) { // Group does not have any balance line, nothing to pay
		$retval['success'] = FALSE;
		$retval['message'] = "Missing balance for function groupDividend in ".__FILE__." at line ".__LINE__;
		return $retval;
	} elseif(intval($groupBalance) == 0) { // Group does have balance 0, that's rather ok but we still dont pay anything
		$retval['success'] = TRUE;
		return $retval;
	}

	$grouppower	= sprintf("%d",$parameter['groupPower']);
	$query = sprintf("SELECT
							#__opensim_grouprolemembership.AgentID
						FROM
							#__opensim_grouprolemembership,
							#__opensim_grouprole,
							#__opensim_moneybalances
						WHERE
							#__opensim_grouprolemembership.RoleID = #__opensim_grouprole.RoleID
						AND
							#__opensim_grouprolemembership.GroupID = #__opensim_grouprole.GroupID
						AND
							#__opensim_grouprole.GroupID = '%s'
						AND
							#__opensim_grouprole.Powers & %d
						AND
							#__opensim_grouprolemembership.AgentID = #__opensim_moneybalances.`user`
						GROUP BY
							#__opensim_grouprolemembership.AgentID",
		$groupID,
		$grouppower);
	$db->setQuery($query);
	$db->query();
	$num_rows = $db->getNumRows();
	if($num_rows == 0) { // no member??? nobody to pay for
		$retval['success'] = FALSE;
		$retval['message'] = "Nobody in this group?";
	}
	$groupdividend		= intval(floor($groupBalance / $num_rows));
	if($groupMinDividend > $groupdividend) { // The minimum for paying dividend is not reached yet
		$retval['success'] = TRUE;
		$retval['message'] = "Minimum group dividend not reached yet";
		$retval['groupMinDividend'] = $groupMinDividend;
		$retval['groupdividend'] = $groupdividend;
		return $retval;
	}

	// Everything seems to be ok, lets pay the dividend
	$receivers			= $db->loadAssocList();
	foreach($receivers AS $key => $receiver) {
		$parameter 							= array();
		$parameter['amount']				= $groupdividend;
		$parameter['senderID']				= $groupID;
		$parameter['receiverID']			= $receivers[$key]['AgentID'];
		$parameter['clientSessionID']		= null;
		$parameter['clientUUID']			= null;
		$parameter['clientSecureSessionID']	= null;
		$parameter['description']			= "Group Dividend";
		$retval								= TransferMoney($parameter); // Transfer the groupfee from the group to the ones who should get
		if($retval['success'] === FALSE) {
			checkGridBalance("groupDividend1");
			return $retval; // something went wrong?
		}
	}
	checkGridBalance("groupDividend2");
	$retval['success']	= TRUE;
	return $retval;
}

// Todo: "echte" Currency Funktionen

function convert_to_real($wert) {
	return intval($wert);
}

function get_confirm_value() {
	$ipAddress = $_SERVER['REMOTE_ADDR'];
	$key = "1234567883789";
	$confirmvalue = md5($key."_".$ipAddress);
	return $confirmvalue;
}

function buyCurrency($parameter) {
//	$debug = var_export($parameter,TRUE);
//	debugzeile($debug,"buyCurrency");
//	$retval['success']	= TRUE;
	return getCurrencyQuote($parameter);
}

function getCurrencyQuote($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter getCurrencyQuote");
	}

	$amount	   = $parameter['currencyBuy'];
	$cost = convert_to_real($amount);
	$currency = array('estimatedCost'=> $cost, 'currencyBuy'=> $amount);

	$confirmvalue = get_confirm_value();

//	$retval['success'] = TRUE;
//	$retval['currency'] = $currency;
//	$retval['confirm'] = $confirmvalue;

	$retval['success'] = FALSE;
	$retval['errorMessage'] = JText::_('JOPENSIM_MONEY_BUYCURRENCY_MSG');
	$retval['errorURI'] = "http:/"."/".$_SERVER['HTTP_HOST'];
	return $retval;
}

// added this function temporary to find out why sometimes the grid balance is not zero
function checkGridBalance($functionname = "n/a") {
	$query = "SELECT
					SUM(#__opensim_moneybalances.balance) AS gridbalance
				FROM
					#__opensim_moneybalances";
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$row = $db->loadAssoc();
	if($row['gridbalance'] != 0) {
		$query = "SELECT
						MAX(#__opensim_moneytransactions.time) AS transactiontimestamp
					FROM
						#__opensim_moneytransactions";
		$db->setQuery($query);
		$row2 = $db->loadAssoc();
		$debug = var_export($row,TRUE);
		$message = "Since ".$row2['transactiontimestamp']." the grid balance is ".$row['gridbalance']."\n\nFunction: ".$functionname."\n\n".$debug;
		mail("info@powerdesign.at","jOpenSimWorld Grid Balance Warning",$message);
	}
}

function clientInfo($parameter) {
	if(_JOPENSIMMONEYDEBUG === TRUE) {
		$debug = var_export($parameter,TRUE);
		debugzeile($debug,"Parameter clientInfo");
	}
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
	$query = sprintf("INSERT INTO #__opensim_clientinfo (PrincipalID,userName,grid,remoteip,lastseen,`from`) VALUES ('%1\$s','%2\$s','%3\$s','%4\$s',NOW(),'M')
						ON DUPLICATE KEY UPDATE userName = '%2\$s', grid = '%3\$s', remoteip = '%4\$s', lastseen = NOW(), `from` = 'M'",
		$uuid,
		mysql_real_escape_string(trim($username)),
		$host,
		$userip);
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$db->query();
}

?>