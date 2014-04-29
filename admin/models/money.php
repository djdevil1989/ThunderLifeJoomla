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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'opensim.php');

class OpenSimCpModelMoney extends OpenSimCpModelOpenSim {
	public $_moneySettingsData;
	public $filename		= "money.php";
	public $view			= "money";

	public function __construct($config = array()) {
//		$config['filter_fields'] = $this->searchInFields;
		parent::__construct($config);
	}

	protected function getListQuery() {
		$db		=& JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__opensim_moneytransactions.*');
		$query->from('#__opensim_moneytransactions');

		// Filter sender, receiver and ornames
		$sender		= $db->escape($this->getState('filter.sender'));
		$receiver	= $db->escape($this->getState('filter.receiver'));
		$orname		= $db->escape($this->getState('filter.orname'));
		if($orname == "or") {
			if(!empty($sender) && !empty($receiver)) {
				$query->where('(#__opensim_moneytransactions.sender = '.$db->quote($sender).' OR #__opensim_moneytransactions.receiver = '.$db->quote($receiver).')');
			} elseif(!empty($sender) && empty($receiver)) {
				$query->where('(#__opensim_moneytransactions.sender = '.$db->quote($sender).' OR #__opensim_moneytransactions.receiver = '.$db->quote($sender).')');
			} elseif(empty($sender) && !empty($receiver)) {
				$query->where('(#__opensim_moneytransactions.sender = '.$db->quote($receiver).' OR #__opensim_moneytransactions.receiver = '.$db->quote($receiver).')');
			}
		} else {
			if (!empty($sender)) {
				$query->where('#__opensim_moneytransactions.sender = '.$db->quote($sender));
			}
			if (!empty($receiver)) {
				$query->where('#__opensim_moneytransactions.receiver = '.$db->quote($receiver));
			}
		}

		// Filter daterange
		$daterange = $db->escape($this->getState('filter.daterange'));
		if (!empty($daterange)) {
			$query->where('#__opensim_moneytransactions.`time` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL '.(int)$daterange.' DAY))');
		}

		$query->order('#__opensim_moneytransactions.`time` DESC');

		return $query;
	}

	public function getItems() {
		$items = parent::getItems();
		$items = $this->getTransactionOpenSimNames($items);
		return $items;
	}

	public function storeMoneySettings() {
		$currencyname		= JRequest::getVar( 'jopensim_money_name',				'',	'method',	'string');
		$bankeraccount		= JRequest::getVar( 'jopensim_money_bankeraccount',		'',	'method',	'string');
		$bankername			= JRequest::getVar( 'jopensim_money_bankername',		'',	'method',	'string');
		$startbalance		= JRequest::getVar( 'jopensim_money_startbalance',		0,	'method',	'INTEGER');
		$uploadcharge		= JRequest::getVar( 'jopensim_money_uploadcharge',		0,	'method',	'INTEGER');
		$groupcharge		= JRequest::getVar( 'jopensim_money_groupcharge',		0,	'method',	'INTEGER');
		$groupmindividend	= JRequest::getVar( 'jopensim_money_groupmindividend',	0,	'method',	'INTEGER');

		$this->saveMoneyConfigValue("name",$currencyname);
		$this->saveMoneyConfigValue("bankerUID",$bankeraccount);
		$this->saveMoneyConfigValue("bankerName",$bankername);
		$this->saveMoneyConfigValue("startBalance",$startbalance);
		$this->saveMoneyConfigValue("uploadCharge",$uploadcharge);
		$this->saveMoneyConfigValue("groupCharge",$groupcharge);
		$this->saveMoneyConfigValue("groupMinDividend",$groupmindividend);
	}

	public function saveMoneyConfigValue($name,$value) {
		$query = sprintf("INSERT INTO #__opensim_moneysettings (#__opensim_moneysettings.`field`,#__opensim_moneysettings.value) VALUES ('%1\$s','%2\$s') ON DUPLICATE KEY UPDATE #__opensim_moneysettings.value = '%2\$s'",
					$name,
					$value);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function getBankerUser() {
		$filter['UserLevel'] = -2;
		$this->userquery = $this->opensim->getUserQuery($filter,null,null,1);
		$bankeruser = $this->getUserDataList();
//		$bankeruser['query'] = $this->userquery;
		return $bankeruser;
	}

	public function getBalance($uuid) {
		if($this->_moneySettingsData['startBalance'] > 0) $init = $this->_moneySettingsData['startBalance'];
		else $init = 0;
		$this->balanceExists($uuid,$init); // see if $uuid exists and if not, create a balance line for it
		$query					= sprintf("SELECT balance FROM #__opensim_moneybalances WHERE `user` = '%s'",$uuid);
		$db						=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		$userbalance			= $db->loadAssoc();
		$balance				= $userbalance['balance'];
		return $balance;
	}

	public function setBalance($uuid,$amount) {
		$this->balanceExists($uuid); // see if $uuid exists and if not, create a zero balance line for it
		$query = sprintf("UPDATE #__opensim_moneybalances SET balance = balance + %d WHERE `user`= '%s'",$amount,$uuid);
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function balanceExists($uuid,$amount = 0) { // if this $uuid does not exist yet, it will create a Balance with $amount for it
		$query	= sprintf("SELECT balance FROM #__opensim_moneybalances WHERE `user` = '%s'",$uuid);
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();
		if($num_rows == 0) {
			$query = sprintf("INSERT INTO #__opensim_moneybalances (`user`,`balance`) VALUES ('%s','%d')",$uuid,$amount);
			$db->setQuery($query);
			$db->query();
		}
	}

	public function getAllBalances($banker = null) {
		if(!$banker) $query = "SELECT SUM(#__opensim_moneybalances.balance) AS allbalances FROM #__opensim_moneybalances";
		else $query = sprintf("SELECT SUM(#__opensim_moneybalances.balance) AS allbalances FROM #__opensim_moneybalances WHERE user != '%s'",$banker);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$summe = $db->loadResult();
		return $summe;
	}

	public function getPositiveBalances($banker = null) {
		if(!$banker) $query = "SELECT #__opensim_moneybalances.balance, #__opensim_moneybalances.user FROM #__opensim_moneybalances WHERE #__opensim_moneybalances.balance > 0";
		else $query = sprintf("SELECT #__opensim_moneybalances.balance, #__opensim_moneybalances.user FROM #__opensim_moneybalances WHERE #__opensim_moneybalances.balance > 0 AND #__opensim_moneybalances.user != '%s'",$banker);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$positives = $db->loadAssocList();
		return $positives;
	}

	public function removeUserBalance($uuid) {
		$query = sprintf("DELETE FROM #__opensim_moneybalances WHERE #__opensim_moneybalances.user = '%s'",$uuid);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function setBankerBalance() {
		$banker		= JRequest::getString('bankerUID');
		$balance	= JRequest::getInt('bankerbalance');
		$balance	*= -1;

		$query = sprintf("UPDATE #__opensim_moneybalances SET #__opensim_moneybalances.balance = '%d' WHERE #__opensim_moneybalances.user = '%s'",$balance,$banker);
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}

	public function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		//Filter (dropdown) sender
		$state = $this->getUserStateFromRequest($this->context.'.filter.sender', 'filter_sender', '', 'string');
		$this->setState('filter.sender', $state);

		//Filter (dropdown) receiver
		$state = $this->getUserStateFromRequest($this->context.'.filter.receiver', 'filter_receiver', '', 'string');
		$this->setState('filter.receiver', $state);

		//Filter (checkbox) senderORreceiver
		$state = $this->getUserStateFromRequest($this->context.'.filter.orname', 'filter_orname', '', 'string');
		$this->setState('filter.orname', $state);

		//Filter (dropdown) daterange
		$state = $this->getUserStateFromRequest($this->context.'.filter.daterange', 'filter_daterange', '', 'string');
		$this->setState('filter.daterange', $state);

		//Takes care of states: list. limit / start / ordering / direction
		parent::populateState($ordering,$direction);
	}

}
?>