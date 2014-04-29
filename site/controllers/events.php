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

class OpenSimControllerevents extends OpenSimController {
	function __construct() {
		parent::__construct();
		$model = $this->getModel('events');
	}

	function insertevent() {
		$model = $this->getModel('events');
		$opensim = $model->opensim;
//		$data = JRequest::get('post');
		$data['eventname']		= trim(JRequest::getVar('eventname'));
		$data['eventdate']		= trim(JRequest::getVar('eventdate'));
		$data['eventtime']		= trim(JRequest::getVar('eventtime'));
		$data['eventtimezone']	= trim(JRequest::getVar('eventtimezone'));
		$data['eventduration']	= trim(JRequest::getVar('eventduration'));
		$data['eventlocation']	= trim(JRequest::getVar('eventlocation'));
		$data['eventcategory']	= trim(JRequest::getVar('eventcategory'));
		$data['covercharge']	= trim(JRequest::getVar('covercharge'));
		$data['description']	= trim(JRequest::getVar('description'));
		$data['eventflags']		= trim(JRequest::getVar('eventflags'));
		$data['parceluuid']		= trim(JRequest::getVar('eventlocation'));
		$retval = $model->insertEvent($data);
		if($retval['error'] > 0) {
			$message	= "";
			if($retval['error'] & 1) $message .= JText::_(ERROR_NOEVENTNAME);
			if($retval['error'] & 2) $message .= JText::_(ERROR_NOEVENTDATE);
			if($retval['error'] & 4) $message .= JText::_(ERROR_NOEVENTUSER);
			$layout		= "submitevent";
			$type		= "Error";
			$addvalues	= "&eventname=".$data['eventname']."&eventdate=".$data['eventdate']."&eventtime=".$data['eventtime']."&eventtimezone=".$data['eventtimezone']."&eventduration=".$data['eventduration']."&eventlocation=".$data['eventlocation']."&eventcategory=".$data['eventcategory']."&covercharge=".$data['covercharge']."&description=".$data['description']."&eventflags=".$data['eventflags'];
		} else {
			$type		= "message";
			$message	= JText::_(OK_EVENTCREATED);
			$layout		= "eventlist";
			$addvalues	= "";
		}
		$redirect = "index.php?option=com_opensim&view=events&layout=".$layout.$addvalues;
		$this->setRedirect($redirect,$message,$type);
	}

	function updateevent() {
		$type		= "message";
		$message	= JText::_(TODO);
		$redirect	= "index.php?option=com_opensim&view=events&layout=eventlist";
		$this->setRedirect($redirect,$message,$type);
	}

	public function deleteevent() {
		$eventid	= JRequest::getVar('eventid');
		$model		= $this->getModel('events');
		$retval		= $model->deleteEvent($eventid);
		if($retval['error'] > 0) {
			if($retval['error'] & 4) $message .= JText::_(ERROR_NOEVENTUSER);
			$layout		= "eventlist";
			$type		= "Error";
			$message	= "";
		} else {
			$type		= "message";
			$message	= JText::_(OK_EVENTDELETED);
			$layout		= "eventlist";
		}
		$redirect = "index.php?option=com_opensim&view=events&layout=".$layout;
		$this->setRedirect($redirect,$message,$type);
	}
}
?>
