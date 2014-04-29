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

class OpenSimCpControllerOpenSim extends OpenSimCpController {

	public $model;

	public function __construct() {
		parent::__construct();
		$this->model	= $this->getModel('opensimcp');
	}

	public function savecss() {
//		$model	= $this->getModel('opensimcp');
		$retval	= $this->model->saveCSS();
		$redirect	= "index.php?option=com_opensim&view=opensimcp";
		$type		= $retval['type'];
		$message	= $retval['message'];
		$this->setRedirect($redirect,$message,$type);
	}

	public function applycss() {
//		$model	= $this->getModel('opensimcp');
		$retval	= $this->model->saveCSS();
		$redirect	= "index.php?option=com_opensim&view=opensimcp&task=editcss";
		$type		= $retval['type'];
		$message	= $retval['message'];
		$this->setRedirect($redirect,$message,$type);
	}

	public function cancel() {
		$redirect	= "index.php?option=com_opensim&view=opensimcp";
		$this->setRedirect($redirect);
	}
}
?>