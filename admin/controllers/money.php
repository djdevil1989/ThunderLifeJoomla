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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OpenSimCpControllerMoney extends OpenSimCpController {

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function cancel() {
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp');
	}

	public function savemoneysettings() {
		$this->save();
	}

	public function save() {
		$model = $this->getModel('money');
		$model->storeMoneySettings();
		$this->setRedirect('index.php?option=com_opensim&view=money',JText::_('SETTINGSSAVEDOK'));
	}

	public function apply_settings() {
		$model = $this->getModel('money');
		$model->storeMoneySettings();
		$this->setRedirect('index.php?option=com_opensim&view=money',JText::_('SETTINGSSAVEDOK'));
	}

	public function cancelMoneySettings() {
		$this->cancel();
	}

	public function cancelMoneyFromBanker() {
		$returnto = JRequest::getString('returnto');
		$this->setRedirect('index.php?option=com_opensim&view='.$returnto);
	}

	public function balancecorrection() {
		$model = $this->getModel('money');
		$model->setBankerBalance();
		$this->setRedirect('index.php?option=com_opensim&view=money');
	}

	public function resetmoney() {
		$model = $this->getModel('money');
		$settings = $model->getMoneySettings();
		$banker = $settings['bankerUID'];
		$positives = $model->getPositiveBalances($banker);
		if(is_array($positives) && count($positives) > 0) {
			foreach($positives AS $data) {
				if($model->opensimCreated($data['user']) === FALSE) { // this user does not exist in OpenSim anymore, lets remove it
					$model->removeUserBalance($data['user']);
				} else {
					$parameter['senderID']		= $data['user'];
					$parameter['receiverID']	= $banker;
					$parameter['amount']		= $data['balance'];
					$parameter['description']	= "jOpenSim MoneyReset!";
					$model->TransferMoney($parameter);
				}
			}
		}
		$bankerbalance = $model->getBalance($banker);
		$model->setBalance($banker,($bankerbalance * -1));
		$type = "message";
		$message = JText::_('JOPENSIM_MONEY_RESET_MESSAGE');
		$this->setRedirect('index.php?option=com_opensim&view=money',$message,$type);
	}
}
?>