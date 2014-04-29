<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component jOpenSim Component
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldjOpenSimHead extends JFormField
{
	protected $type = 'jOpenSimHead';
	
	protected function getInput() {
		// Temporary solution
		JHTML::stylesheet( 'administrator/components/com_opensim/assets/jopensimoptions.css' );
		return parent::getLabel();
	}
	
	protected function getLabel() {
	
		// Temporary solution
		JHTML::stylesheet( 'administrator/components/com_opensim/assets/jopensimoptions.css' );
		return parent::getLabel();
	}
}
?>