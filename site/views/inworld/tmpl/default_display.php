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
 
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('INWORLD_DETAILS'); ?></h1>
<table cellspacing='0' cellpadding='0'>
<tr>
	<td>
	<?php echo $this->topbar; ?>
	</td>
</tr>
<tr>
	<td>
	<form name='opensimdetails' action='index.php' method='post'>
	<input type='hidden' name='option' value='com_opensim' />
	<input type='hidden' name='view' value='inworld' />
	<input type='hidden' name='task' value='update' />
	<input type='hidden' name='uuid' value='<?php echo $this->osdata['uuid']; ?>' />
	<input type='hidden' name='Itemid' value='<?php echo $this->Itemid; ?>' />
	<input type='hidden' name='oldlastname' value='<?php echo $this->osdata['lastname']; ?>' />
	<table class='userdetailtable<?php echo $this->pageclass_sfx; ?>'>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td class='<?php echo $this->pageclass_sfx; ?>'><label for='firstname'><?php echo JText::_('FIRST_NAME'); ?>:</label></td>
		<td class='<?php echo $this->pageclass_sfx; ?>'><?php echo ($this->ossettings['userchange'] & 1) ? "<input type='text' name='firstname' id='firstname' value='".$this->osdata['firstname']."' />":$this->osdata['firstname']; ?></td>
	</tr>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td class='<?php echo $this->pageclass_sfx; ?>'><label for='lastname'><?php echo JText::_('LAST_NAME'); ?>:</label></td>
		<td class='<?php echo $this->pageclass_sfx; ?>'><?php echo ($this->ossettings['userchange'] & 2) ? $this->lastnamefield:$this->osdata['lastname']; ?></td>
	</tr>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td class='<?php echo $this->pageclass_sfx; ?>'><label for='email'><?php echo JText::_('EMAIL'); ?>:</label></td>
		<td class='<?php echo $this->pageclass_sfx; ?>'><?php echo ($this->ossettings['userchange'] & 4) ? "<input type='text' name='email' id='email' value='".$this->osdata['email']."' />":$this->osdata['email']; ?></td>
	</tr>
	<?php if($this->ossettings['addons'] & 1): ?>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td class='<?php echo $this->pageclass_sfx; ?>'><input type='checkbox' name='im2email' id='im2email' value='1' <?php echo ($this->osdata['im2email'] == 1) ? " checked='checked'":""; ?>/></td>
		<td class='<?php echo $this->pageclass_sfx; ?>'><label for='im2email'><?php echo JText::_('SENDMESSAGE2EMAIL'); ?></label></td>
	</tr>
	<?php endif; ?>
	<tr>
		<td valign='top'><label for='timezone'><?php echo JText::_('JOPENSIM_TIMEZONE'); ?>:</label></td>
		<td>
		<select name='timezone' id='timezone' >
<?php if(is_array($this->timezones) && count($this->timezones) > 0): ?>
<?php foreach($this->timezones AS $timezone): ?>
			<option value='<?php echo $timezone; ?>' <?php echo ($timezone == $this->osdata['timezone']) ? " selected='selected'":""; ?>><?php echo $timezone; ?></option>
<?php endforeach; ?>
<?php else: ?>
			<option value='???'><?php echo JText::_('JOPENSIM_TIMEZONE_ERROR'); ?></option>
<?php endif; ?>
		</select>
		</td>
	</tr>
	<?php if($this->ossettings['userchange'] & 8): ?>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td class='<?php echo $this->pageclass_sfx; ?>'><label for='pwd1'><?php echo JText::_('PASSWORD'); ?>:</label></td>
		<td class='<?php echo $this->pageclass_sfx; ?>'><input type='password' id='pwd1' name='pwd1' value='' /></td>
	</tr>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td class='<?php echo $this->pageclass_sfx; ?>'><label for='pwd2'><?php echo JText::_('PASSWORD2'); ?>:</label></td>
		<td class='<?php echo $this->pageclass_sfx; ?>'><input type='password' id='pwd2' name='pwd2' value='' /></td>
	</tr>
	<?php endif; ?>
	<?php if($this->ossettings > 0): ?>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td colspan='2' class='<?php echo $this->pageclass_sfx; ?>'>&nbsp;</td>
	</tr>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td colspan='2' class='<?php echo $this->pageclass_sfx; ?>'>
		<input type='submit' class='button' value='<?php echo JText::_('SAVECHANGES'); ?>' class='<?php echo $this->pageclass_sfx; ?>' />
		</td>
	</tr>
	<?php endif; ?>
	</table>
	</form>
	</td>
</tr>
</table>
<?php
// debugprint($this->user,"\$this->user");
// debugprint($this->osdata,"\$this->osdata");
// debugprint($_SESSION,"\$_SESSION");
?>