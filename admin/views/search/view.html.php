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
 
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
class opensimcpViewsearch extends JView {
	public function display($tpl = null) {
		JHTML::_('behavior.modal');
		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );
		$model = $this->getModel('search');
		$settings = $model->getSettingsData();
		$searchoptions = $model->getoptions();
		$searchsort = $model->getoptions("customsort");
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		$assetinfo = pathinfo(JPATH_COMPONENT_ADMINISTRATOR);
		$assetpath = "components".DS.$assetinfo['basename'].DS."assets".DS;
		$this->assignRef('assetpath',$assetpath);
		$this->assignRef('searchoptions',$searchoptions);
		$this->assignRef('searchsort',$searchsort);

		switch($task) {
			default:
			break;
		}
// 		$this->assignRef( 'zusatztext', $zusatztext );

		$this->_setToolbar($tpl);
		parent::display($tpl);
	}

	public function _setToolbar($tpl) {
		JToolBarHelper::title(JText::_('JOPENSIM_MENU_SEARCH'),'ossearch');

		switch($tpl) {
			case "blabla":
			break;
			default:
				JToolBarHelper::apply('applysearch');	
				JToolBarHelper::save('savesearch');
				JToolBarHelper::cancel('cancel','JCANCEL');
			break;
		}
		JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_SEARCH'));
	}
}

?>