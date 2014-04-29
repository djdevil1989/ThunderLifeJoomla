<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Form Field class for jOpenSim
 */
class JFormFieldornameselector extends JFormFieldList {
	/**
	* The field type.
	*
	* @var         string
	*/
	protected $type = 'ornameselector';


	/**
	* Method to get a list of options for a list input.
	*
	* @return      array           An array of JHtml options.
	*/
	public function getOptions() {
		$values = $this->getValues();
		$options = array();
		foreach($values as $value => $text) {
			$zaehler = count($options);
			$options[$zaehler]->value	= $value;
			$options[$zaehler]->text	= JText::_($text);
		}
		return $options;
	}

	public function getValues() {
		$values = array();
		$value['and']		= "JOPENSIM_MONEY_TRANSACTIONS_FILTER_ORNAMES_AND";
		$value['or']		= "JOPENSIM_MONEY_TRANSACTIONS_FILTER_ORNAMES_OR";
		return $value;
	}
}
?>