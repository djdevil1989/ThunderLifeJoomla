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

$_REQUEST['tmpl'] = "component";

class opensimViewopensim extends JView {

	function display($tpl = null) {
		unset($_SERVER['HTTP_USER_AGENT']); // the HTTP_USER_AGENT is most probably some viewer, we dont need any notices about it
		JHTML::stylesheet( 'opensim.css', 'components/com_opensim/assets/' );
		JHTML::stylesheet( 'opensim.override.css', 'components/com_opensim/assets/' );
		$model = $this->getModel('opensim');
//		$params = &JComponentHelper::getParams('mod_opensim_gridstatus');

		$this->assignRef('params',$params);

		$gridstatus = $model->getGridStatus();
		$this->assignRef('gridstatus',$gridstatus);

		$mapinfo = pathinfo(JPATH_COMPONENT);
		$assetpath = "components".DS.$mapinfo['basename'].DS."assets".DS;
		$this->assignRef('assetpath',$assetpath);

		$regions = $model->getData();
		$settingsdata = $model->getSettingsData();

		if(intval($settingsdata['hiddenregions']) == 0) {
			$regionarray = $model->removehidden($regions['regions']);
		}
		$this->assignRef('regions',$regionarray);

		/*$settingsdata =& $this->get('Data');*/
		$this->assignRef('settingsdata',$settingsdata);
		$this->assignRef('mapserver',$settingsdata['oshost']);
		$this->assignRef('mapport',$settingsdata['osport']);

		$task = JRequest::getVar( 'task', '', 'method', 'string');
		switch($task) {
			default:
			break;
		}

		parent::display($tpl);
	}
}
?>