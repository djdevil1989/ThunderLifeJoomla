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

		$model					= $this->getModel('money');
		$this->settings			= $model->getSettingsData();
		$this->moneysettings	= $model->getMoneySettings();
		$this->opensimdb		= $model->getOpenSimGridDB();

		if($model->moneyEnabled !== TRUE) {
			$tpl = "disabled";
		} else {
			$task = JRequest::getVar( 'task', '', 'method', 'string');

			$moneysettings		= $model->getMoneySettings();
			$this->bankerlist	= $model->getBankerUser();
			$this->balanceAll	= $model->getAllBalances();
			if(!$moneysettings['bankerUID']) {
				JError::raiseWarning(100,JText::_('JOPENSIM_MONEY_BANKERWARNING'));
			}
			if($this->balanceAll != 0) {
				JError::raiseNotice(100,JText::_('JOPENSIM_MONEY_BALANCEWARNING'));
			}
			$this->balanceUser	= $model->getAllBalances($moneysettings['bankerUID']);

			switch($task) {
				case "moneyconfig":
					$tpl = "settings";
				break;
				case "userMoney":
					$this->returnto = "user";
					$tpl = "usermoney";
					$useruuid				= JRequest::getVar( 'uuid', '', 'method', 'string');
					$this->balance			= $model->getBalance($useruuid);
					$this->userdata			= $model->getUserData($useruuid);
				break;
				case "moneyUser":
					$this->returnto = "money";
					$tpl = "usermoney";
					$useruuid				= JRequest::getVar( 'uuid', '', 'method', 'string');
					$this->balance			= $model->getBalance($useruuid);
					$this->userdata			= $model->getUserData($useruuid);
				break;
				default:
					// Get data from the model
					$items = $this->get('Items');
					$pagination = $this->get('Pagination');

					// Check for errors.
					if (count($errors = $this->get('Errors'))) {
						JError::raiseError(500, implode('<br />', $errors));
						return false;
					}
					// Assign data to the view
					$this->items		= $items;
					$this->state		= $this->get('State');
					$this->pagination	= $pagination;
					$this->searchterms	= $this->state->get('filter.search');
				break;
			}
		}

		$this->setToolbar($tpl);
		parent::display($tpl);
	}

	public function setToolbar($tpl) {
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