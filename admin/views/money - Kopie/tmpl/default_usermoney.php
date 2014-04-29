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
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='<?php echo $this->returnto; ?>' />
<input type='hidden' name='task' value='<?php echo $this->task; ?>' />
<input type='hidden' name='limit' value='<?php echo $this->limit; ?>' />
<!--<input type="hidden" name="limitstart" value="0" />-->
<input type='hidden' name='jopensim_money_userid' value='<?php echo $this->userdata['uuid']; ?>' />
<input type='hidden' name='uuid' value='<?php echo $this->userdata['uuid']; ?>' />
<input type='hidden' name='returnto' value='<?php echo $this->returnto; ?>' />
<h1><?php echo JText::_('JOPENSIMUSERMONEY'); ?></h1>
<h3><?php echo JText::_('ADDONS_MONEY'); ?></h3>
<table>
<tr>
	<td><label><?php echo JText::sprintf('JOPENSIM_MONEY_CURRENTBALANCE',$this->userdata['firstname']." ".$this->userdata['lastname']); ?>:</label></td>
	<td><?php echo number_format($this->balance,0,JTEXT::_('JOPENSIM_MONEY_SEPERATOR_COMMA'),JTEXT::_('JOPENSIM_MONEY_SEPERATOR_THOUSAND')); ?></td>
</tr>
<tr>
	<td><label for='jopensim_money_payment'><?php echo JText::_('JOPENSIM_MONEY_PAY'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_payment' name='jopensim_money_payment' size='5' class='number_field' value='0' /></td>
</tr>
<tr>
	<td><label for='jopensim_money_bankeraccount'><?php echo JText::_('JOPENSIM_MONEY_PAYFROM'); ?>:</label></td>
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
	<td><label for='jopensim_money_paytext'><?php echo JText::_('JOPENSIM_MONEY_PAYTEXT'); ?>:</label></td>
	<td><input type='text' id='jopensim_money_paytext' name='jopensim_money_paytext' size='40' value='<?php echo JText::_('JOPENSIM_MONEY_PAYTEXTDEFAULT'); ?>' /></td>
</tr>
</table>
<?php if(is_array($this->transactionlist) && count($this->transactionlist) > 0): ?>
<table class="adminlist">
<thead><?php echo $this->loadTemplate('transactionhead');?></thead>
<tbody><?php echo $this->loadTemplate('transactionbody');?></tbody>
<tfoot><?php echo $this->loadTemplate('transactionfoot');?></tfoot>
</table>
</form>
<?php else: ?><br /><br /><br />
<?php echo JText::_('JOPENSIM_MONEY_USER_NOTRANSACTIONS'); ?>
<?php endif; ?>
<pre>
#####
<?php
var_dump($_REQUEST);
?>
#####
<?php
var_dump($this->pagination);
?>

</pre>