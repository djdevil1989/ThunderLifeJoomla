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
?>
<h3><?php echo JText::_('ADDONS_MONEY'); ?></h3>
<form action="index.php" method="post" name="adminForm" target='_parent'>
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="money" />
<input type="hidden" name="task" value="savemoneysettings" />

<table width='100%'>
<tr>
	<td><label for='jopensim_money_name'><?php echo JText::_('JOPENSIM_MONEY_NAME'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_name' name='jopensim_money_name' size='3' maxlength='3' value='<?php echo $this->moneysettings['name']; ?>' /></td>
</tr>
<tr>
	<td><label for=''><?php echo JText::_('JOPENSIM_MONEY_BANKERACCOUNT'); ?>:</label></td>
	<td>
	<select name='jopensim_money_bankeraccount'>
<?php
if(is_array($this->bankerlist) && count($this->bankerlist) > 0) {
	foreach($this->bankerlist AS $banker) {
?>
		<option value='<?php echo $banker['userid']; ?>'<?php echo ($banker['userid'] == $this->moneysettings['bankerUID']) ? " selected='selected'":""; ?>><?php echo $banker['firstname']." ".$banker['lastname']; ?></option>
<?php
	}
} else {
?>
		<option value=''><?php echo JText::_('JOPENSIM_MONEY_ERROR_NOBANKER'); ?></option>
<?php
	if($this->moneysettings['bankerUID']) {
?>
		<option value='<?php echo $this->moneysettings['bankerUID']; ?>' selected='selected'><?php echo JText::_('JOPENSIM_MONEY_OLDBANKER'); ?></option>
<?php
	}
}
?>
	</select>
	</td>
</tr>
<tr>
	<td><label for='jopensim_money_bankername'><?php echo JText::_('JOPENSIM_MONEY_BANKERNAME_DESC'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_bankername' name='jopensim_money_bankername' size='25' value='<?php echo $this->moneysettings['bankerName']; ?>' /></td>
</tr>
<tr>
	<td><label for='jopensim_money_startbalance'><?php echo JText::_('JOPENSIM_MONEY_STARTBALANCE'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_startbalance' name='jopensim_money_startbalance' size='5' class='number_field' value='<?php echo $this->moneysettings['startBalance']; ?>' /></td>
</tr>
<tr>
	<td><label for='jopensim_money_uploadcharge'><?php echo JText::_('JOPENSIM_MONEY_UPLOADCHARGE'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_uploadcharge' name='jopensim_money_uploadcharge' size='3' class='number_field' value='<?php echo $this->moneysettings['uploadCharge']; ?>' /></td>
</tr>
<tr>
	<td><label for='jopensim_money_groupcharge'><?php echo JText::_('JOPENSIM_MONEY_GROUPCHARGE'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_groupcharge' name='jopensim_money_groupcharge' size='3' class='number_field' value='<?php echo $this->moneysettings['groupCharge']; ?>' /></td>
</tr>
<tr>
	<td><label for='jopensim_money_groupmindividend'><?php echo JText::_('JOPENSIM_MONEY_GROUPMINDIVIDEND'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_groupmindividend' name='jopensim_money_groupmindividend' size='3' class='number_field' value='<?php echo $this->moneysettings['groupMinDividend']; ?>' /></td>
</tr>
<tr>
	<td><label><?php echo JText::_('JOPENSIM_MONEY_BALANCEALL'); ?>:</label></td>
	<td><?php echo number_format($this->balanceAll,0,JTEXT::_('JOPENSIM_MONEY_SEPERATOR_COMMA'),JTEXT::_('JOPENSIM_MONEY_SEPERATOR_THOUSAND')); ?></td>
</tr>
<tr>
	<td><label><?php echo JText::_('JOPENSIM_MONEY_BALANCEUSER'); ?>:</label></td>
	<td><?php echo number_format($this->balanceUser,0,JTEXT::_('JOPENSIM_MONEY_SEPERATOR_COMMA'),JTEXT::_('JOPENSIM_MONEY_SEPERATOR_THOUSAND')); ?></td>
</tr>
<tr>
	<td colspan='2' align='right'>
	<input type='submit' name='submit' value='<?php echo JText::_('JSAVE'); ?>' />&nbsp;
	<input type='reset' name='reset' value='<?php echo JText::_('JOPENSIM_RESET'); ?>' />&nbsp;
	</td>
</tr>
</table>
</form>
<br /><br /><br /><br /><br />
<?php if($this->moneysettings['bankerUID']): ?>
<?php if($this->balanceAll != 0): ?>
<form action="index.php" method="post" name="balancecorrectionForm" onSubmit='return confirm("<?php echo JText::sprintf('JOPENSIM_MONEY_BALANCECORRECTION',$this->moneysettings['name'],($this->balanceUser*-1)); ?>");' target='_parent'>
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="money" />
<input type="hidden" name="task" value="balancecorrection" />
<input type="hidden" name="bankerbalance" value="<?php echo $this->balanceUser; ?>" />
<input type="hidden" name="bankerUID" value="<?php echo $this->moneysettings['bankerUID']; ?>" />
<input type='submit' name='submit' value='<?php echo JText::_('JOPENSIM_MONEY_BALANCECORRECTION_BUTTON'); ?>' />
</form>
<?php endif; ?>
<?php endif; ?>
<!--
<form action="index.php" method="post" name="resetForm" onSubmit='return confirm("<?php echo JText::_('JOPENSIM_MONEY_RESET_SURE'); ?>");'>
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="money" />
<input type="hidden" name="task" value="resetmoney" />
<input type='submit' name='submit' value='<?php echo JText::_('JOPENSIM_MONEY_RESET_BUTTON'); ?>' />
</form>
-->
<?php
// debugprint($this->bankerlist,"bankerlist");
// debugprint($this->settings,"settings");
// debugprint($this->userdata,"userdata");
// debugprint($this->model,"model");
// debugprint($this->usermodel,"usermodel");
?>
