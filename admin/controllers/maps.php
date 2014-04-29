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

class OpenSimCpControllerMaps extends OpenSimCpController {
	function __construct() {
		parent::__construct();
		$model = $this->getModel('settings');
	}

	function savedefault() {
/*
		$data = JRequest::get('post');
		debugprint($data);
		exit;
*/
		$model = $this->getModel('maps');
		$model->savedefault();
		$this->setRedirect( 'index.php?option=com_opensim&view=maps&task=applyok',JText::_('REGION_SAVED'));
	}

	function savemanual() {
		$model = $this->getModel('maps');
		$model->savemanual();
		$this->setRedirect('index.php?option=com_opensim&view=maps&task=applyok',JText::_('REGION_SAVED'));
	}

	function setDefaultRegion() {
		$data = JRequest::get('post');
		$this->setRedirect('index.php?option=com_opensim&view=maps&task=selectdefault&region='.$data['selectedRegion']);
	}

	function apply_regionsettings() {
		$data = JRequest::get('request');
		$model = $this->getModel('maps');
		$model->setMapInfo($data);
		$this->setRedirect('index.php?option=com_opensim&view=maps&task=edit&selectedRegion='.$data['regionUUID'],JText::_('REGIONINFO_SAVED'));
	}

	function save_regionsettings() {
		$data = JRequest::get('request');
		$model = $this->getModel('maps');
		$model->setMapInfo($data);
		$this->setRedirect('index.php?option=com_opensim&view=maps',JText::_('REGIONINFO_SAVED'));
	}

	function removemaparticle() {
		$data = JRequest::get('request');
		$model = $this->getModel('maps');
		$model->removeMapArticle($data['regionUUID']);
		$this->setRedirect('index.php?option=com_opensim&view=maps&task=edit&selectedRegion='.$data['regionUUID'],JText::_('REGIONARTICLE_REMOVED'));
	}

	function setRegionVisible() {
		$data = JRequest::get('request');
		$region = $data['region'];
		$model = $this->getModel('maps');
		$model->setVisible($region,0);
		$this->setRedirect('index.php?option=com_opensim&view=maps');
	}

	function setRegionInvisible() {
		$data = JRequest::get('request');
		$region = $data['region'];
		$model = $this->getModel('maps');
		$model->setVisible($region,1);
		$this->setRedirect('index.php?option=com_opensim&view=maps');
	}

	function save_mapconfig() {
		$data = JRequest::get('request');
		$model = $this->getModel('maps');
		$retval = $model->updateMapconfig($data);
		if($retval === TRUE) {
			$type = "message";
			$message = JText::_('JOPENSIM_SAVE_MAPCONFIG_OK');
		} else {
			$type = "error";
			$message = JText::_('JOPENSIM_SAVE_MAPCONFIG_ERROR');
		}
		$this->setRedirect('index.php?option=com_opensim&view=maps',$message,$type);
	}

	function apply_mapconfig() {
		$data = JRequest::get('request');
		$model = $this->getModel('maps');
		$retval = $model->updateMapconfig($data);
		if($retval === TRUE) {
			$type = "message";
			$message = JText::_('JOPENSIM_SAVE_MAPCONFIG_OK');
		} else {
			$type = "error";
			$message = JText::_('JOPENSIM_SAVE_MAPCONFIG_ERROR');
		}
		$this->setRedirect('index.php?option=com_opensim&view=maps&task=mapconfig',$message,$type);
	}

	function maprefresh() {
		$data = JRequest::get('request');
		$model = $this->getModel('maps');
		$model->refreshMap($data['selectedRegion']);
		$this->setRedirect( 'index.php?option=com_opensim&view=maps');
	}

	function cancel() {
		$this->setRedirect( 'index.php?option=com_opensim&view=maps');
	}


}
?>
