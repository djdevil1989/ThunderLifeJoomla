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
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class opensimcpViewmoney extends JView {
	public function display($tpl = null) {
		JHTML::_('behavior.modal');
		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );
		$model = $this->getModel();
//		$test = $this->setModel('user','OpenSimCpModel');
		$usermodel = $this->getModel('user');
		$userdata = $usermodel->getData();

//		$usermodel = $this->getUserModel();
		$bankerlist = $model->getBankerUser();
		$this->assignRef('model',$model);

		if($model->moneyEnabled === TRUE && !$tpl) $tpl = "money"; // If money is enabled, change default view
		$settings = $model->getSettingsData();
		$moneysettings = $model->getMoneySettings();
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		$assetinfo = pathinfo(JPATH_COMPONENT_ADMINISTRATOR);
		$assetpath = "components".DS.$assetinfo['basename'].DS."assets".DS;
		$this->assignRef('assetpath',$assetpath);
		$this->assignRef('settings',$settings);
		$this->assignRef('moneysettings',$moneysettings);
		$this->assignRef('bankerlist',$bankerlist);

		$this->balanceAll	= $model->getAllBalances();
		$this->balanceUser	= $model->getAllBalances($moneysettings['bankerUID']);

		$this->state		= $this->get('State');
//		$this->daterange	= $this->state->get('filter.daterange');
		$this->daterange	= JRequest::getInt('filter_daterange');
		$this->limit		= JRequest::getInt('limit');
		$this->limitstart	= JRequest::getInt('limitstart');
		if(!$this->limit) $this->limit	= 20;
		$model->setlimits($this->limitstart,$this->limit);
//		if(!$this->daterange) $this->daterange = 30;

		switch($task) {
			case "userMoney":
			case "moneyUser":
				$useruuid				= JRequest::getVar( 'uuid', '', 'method', 'string');
				$balance				= $model->getBalance($useruuid);
				$userdata				= $model->getUserData($useruuid);
				$usertransactions		= $model->getTransactions($useruuid);
				$this->transactionlist	= $model->getTransactionNames($usertransactions,$useruuid);
				$this->assignRef('userdata',$userdata);
				$this->assignRef('balance',$balance);
				$tpl = "usermoney";
				$this->returnto = ($task == "userMoney") ? "user":"money";
			break;
			case "moneyconfig":
				$tpl = "settings";
			break;
			default:
				if($model->moneyEnabled === TRUE) {
					$transactions			= $model->getTransactions(null,$this->daterange);
					$this->transactionlist	= $model->getTransactionNames($transactions);
				}
			break;
		}
		$this->task = $task;
		$this->test = $model->_moneySettingsData['bankerUID'];
		$this->pagination	= $model->getPagination();
		$this->_setToolbar($tpl);
		parent::display($tpl);
	}

	public function _setToolbar($tpl) {
		JToolBarHelper::title(JText::_('JOPENSIM_MONEY'),'osmoney');
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		switch($tpl) {
			case "settings":
				JToolBarHelper::save();
				JToolBarHelper::apply('apply_settings');	
				JToolBarHelper::cancel('cancel','JCANCEL');
			break;
			case "usermoney":
				JToolBarHelper::save('payMoneyFromBanker','JOPENSIM_MONEY_TRANSFERMONEY');
				JToolBarHelper::cancel('cancelMoneyFromBanker','JCANCEL');
				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_USER_MONEY'));
			break;
			default:
//				JToolBarHelper::custom("moneyconfig","moneyconfig","moneyconfig",JText::_('JOPENSIM_MONEY_SETTING'),false,false);
				$bar=& JToolBar::getInstance( 'toolbar' );
				$bar->appendButton( 'Popup', 'moneyconfig', JText::_('JOPENSIM_MONEY_SETTING'), 'index.php?option=com_opensim&view=money&task=moneyconfig&tmpl=component', 550, 400 );
			break;
		}
	}
}

?>