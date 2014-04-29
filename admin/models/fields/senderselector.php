<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
class JFormFieldsenderselector extends JFormFieldList {
	/**
	* The field type.
	*
	* @var         string
	*/

	protected $type = 'senderselector';

	/**
	* Method to get a list of options for a list input.
	*/
	public function getOptions($osdb) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('DISTINCT(#__opensim_moneytransactions.sender)');
		$query->from('#__opensim_moneytransactions');
		$db->setQuery((string)$query);
		$sender = $db->loadResultArray();


		$query = $osdb->getQuery(true);
		$query->select('CONCAT_WS(" ",FirstName,LastName) AS text');
		$query->select('PrincipalID AS value');
		$query->from('UserAccounts');
		$query->order('FirstName,LastName');
		$osdb->setQuery((string)$query);
		$senderlist = $osdb->loadObjectList();

		$options = array();
		if ($senderlist) {
			foreach($senderlist as $senderoption) {
				if(in_array($senderoption->value,$sender)) {
					$zaehler = count($options);
					$options[$zaehler]->value	= $senderoption->value;
					$options[$zaehler]->text	= $senderoption->text;
				}
			}
		}

		return $options;
	}
}
?>