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

// $_REQUEST['tmpl'] = "component";

class opensimViewinworldsearch extends JView {

	function display($tpl = null) {
		JHTML::stylesheet( 'opensim.css', 'components/com_opensim/assets/' );
		$model = $this->getModel('inworldsearch');

		$settingsdata = $model->getSettingsData();
		$this->assignRef('settingsdata',$settingsdata);

		$searchoptions = $model->getSearchOptions();
		$this->assignRef('searchoptions',$searchoptions);

		$itemid		= JRequest::getVar('Itemid');
		$this->assignRef('itemid',$itemid);

		$searchquery = JRequest::getVar('q');
		if(!$searchquery) {
			$showcase = TRUE;
//			$searchresults = 0;
		} else {
			$showcase = FALSE;
//			$results['objects'] = $model->searchobjects($searchquery);
			$result = $model->searchAll($searchquery);
//			$searchresults = count($results['objects']);
		}
		$this->assignRef('result',$result);
		$this->assignRef('searchquery',$searchquery);
		$this->assignRef('showcase',$showcase);
//		$this->assignRef('results',$results);
//		$this->assignRef('searchresults',$searchresults);
		$this->assignRef('jopensimversion',$model->opensim->getversion());

		$task = JRequest::getVar('task','','method','string');

		$results = $model->getResultlines($result);
		$this->assignRef('results',$results);


		switch($task) {
			case "viewersearch":
				$searchform = TRUE;
				$tmpl = TRUE;
				JHTML::stylesheet( 'opensim_inworldsearch.css', 'components/com_opensim/assets/' );
			break;
			default:
				$tmpl = FALSE;
				$searchform = TRUE;
			break;
		}
		$this->assignRef('tmpl',$tmpl);
		$this->assignRef('searchform',$searchform);

		parent::display($tpl);
	}
}
?>