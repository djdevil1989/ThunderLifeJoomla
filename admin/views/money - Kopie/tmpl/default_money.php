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
$daterangeselector		= JFormHelper::loadFieldType('daterangeselector', $this->daterange);
$daterangeOptions		= $daterangeselector->getOptions(); // works only if you set your field getOptions on public!!
?>
<h3><?php echo JText::_('ADDONS_MONEY'); ?></h3>
<?php if(is_array($this->transactionlist) && count($this->transactionlist) > 0): ?>
<form action="<?php echo JRoute::_('index.php?option=com_opensim'); ?>" method="post" name="adminForm">
<input type='hidden' name='view' value='money' />
<fieldset id="filter-bar">
<div class="filter-search fltlft">
</div>
<div class="filter-select fltrt">
	<select name="filter_daterange" id="filter_daterange" class="inputbox" onchange="this.form.submit()">
		<option value=""><?php echo JText::_('JOPENSIM_MONEY_TRANSACTIONS_FILTER_SELECT_DATERANGE'); ?></option>
		<?php echo JHtml::_('select.options',$daterangeOptions,'value','text',$this->daterange);?>
	</select>
</div>
</fieldset>


<table class="adminlist">
<thead><?php echo $this->loadTemplate('transactionhead');?></thead>
<tbody><?php echo $this->loadTemplate('transactionbody');?></tbody>
<tfoot><?php echo $this->loadTemplate('transactionfoot');?></tfoot>
</table>
</form>
<?php else: ?><br /><br /><br />
<?php echo JText::_('JOPENSIM_MONEY_NOTRANSACTIONS'); ?>
<?php endif; ?>
<pre>
#####
<?php
var_dump($this->pagination);
?>

</pre>