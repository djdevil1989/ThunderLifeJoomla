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
defined('_JEXEC') or die('Restricted access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

JFormHelper::loadFieldClass('list');
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$senderselector			= JFormHelper::loadFieldType('senderselector');
$senderOptions			= $senderselector->getOptions($this->opensimdb);

$receiverselector		= JFormHelper::loadFieldType('receiverselector');
$receiverOptions		= $receiverselector->getOptions($this->opensimdb);

$ornameselector		= JFormHelper::loadFieldType('ornameselector');
$ornameOptions		= $ornameselector->getOptions();

$daterangeselector		= JFormHelper::loadFieldType('daterangeselector');
$daterangeOptions		= $daterangeselector->getOptions(); // works only if you set your field getOptions on public!!
?>
<h3><?php echo JText::_('ADDONS_MONEY'); ?></h3>
<p>
<?php echo JText::_('JOPENSIM_MONEY_BALANCEUSER').": ".number_format($this->balanceUser,0,JTEXT::_('JOPENSIM_MONEY_SEPERATOR_COMMA'),JTEXT::_('JOPENSIM_MONEY_SEPERATOR_THOUSAND')); ?>
</p> 
<form action="<?php echo JRoute::_('index.php?option=com_opensim'); ?>" method="post" name="adminForm">
<input type='hidden' name='view' value='money' />
<fieldset id="filter-bar">
<div class="filter-search fltlft">
</div>
<div class="filter-select fltrt">
	<select name="filter_sender" id="filter_sender" class="inputbox" onchange="this.form.submit()">
		<option value=""><?php echo JText::_('JOPENSIM_MONEY_TRANSACTIONS_FILTER_SENDER'); ?></option>
		<?php echo JHtml::_('select.options',$senderOptions,'value','text',$this->state->get('filter.sender'));?>
	</select>
	<select name="filter_orname" id="filter_orname" class="inputbox" onchange="this.form.submit()">
		<?php echo JHtml::_('select.options',$ornameOptions,'value','text',$this->state->get('filter.orname'));?>
	</select>
	<select name="filter_receiver" id="filter_receiver" class="inputbox" onchange="this.form.submit()">
		<option value=""><?php echo JText::_('JOPENSIM_MONEY_TRANSACTIONS_FILTER_RECEIVER'); ?></option>
		<?php echo JHtml::_('select.options',$receiverOptions,'value','text',$this->state->get('filter.receiver'));?>
	</select>
	<select name="filter_daterange" id="filter_daterange" class="inputbox" onchange="this.form.submit()">
		<option value=""><?php echo JText::_('JOPENSIM_MONEY_TRANSACTIONS_FILTER_SELECT_DATERANGE'); ?></option>
		<?php echo JHtml::_('select.options',$daterangeOptions,'value','text',$this->state->get('filter.daterange'));?>
	</select>
</div>
</fieldset>

<?php if(is_array($this->items) && count($this->items) > 0): ?>

<table class="adminlist">
<thead><?php echo $this->loadTemplate('transactionhead');?></thead>
<tbody><?php echo $this->loadTemplate('transactionbody');?></tbody>
<tfoot><?php echo $this->loadTemplate('transactionfoot');?></tfoot>
</table>
</form>
<?php else: ?>
<br /><br /><br />
<?php echo JText::_('JOPENSIM_MONEY_NOTRANSACTIONS'); ?>
<?php endif; ?>