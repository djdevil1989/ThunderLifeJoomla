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
<h1><?php echo JText::_('INWORLD_DETAILS'); ?></h1>
<?php if($this->ossettings['addons'] & 2): ?>
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
	<input type='hidden' name='task' value='updateprofile' />
	<input type='hidden' name='uuid' value='<?php echo $this->osdata['uuid']; ?>' />
	<input type='hidden' name='Itemid' value='<?php echo $this->Itemid; ?>' />
	<table class='profiletable<?php echo $this->pageclass_sfx; ?>'>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label for='aboutText'><?php echo JText::_('PROFILE_ABOUTME'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><textarea name='aboutText' id='aboutText' rows='5' cols='40'><?php echo $this->profiledata['aboutText']; ?></textarea></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label><?php echo JText::_('PROFILE_PARTNER'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $this->profiledata['partnername']; ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label for='aboutText'><?php echo JText::_('PROFILE_URL'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><input type='text' name='profile_url' id='profile_url' size='30' maxlength='255' value='<?php echo $this->profiledata['url']; ?>' /></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' colspan='2' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label><?php echo JText::_('PROFILE_INTERESTS'); ?>:</label></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label><?php echo JText::_('PROFILE_WANTTO'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'>
		<table class='wanttotable'>
		<tr>
			<td><input type='checkbox' name='wantmask[]' id='build' value='<?php echo $this->wantmask['build']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['build']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='build'><?php echo JText::_('WANTTO_BUILD'); ?></label></td>
			<td><input type='checkbox' name='wantmask[]' id='explore' value='<?php echo $this->wantmask['explore']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['explore']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='explore'><?php echo JText::_('WANTTO_EXPLORE'); ?></label></td>
		</tr>
		<tr>
			<td><input type='checkbox' name='wantmask[]' id='meet' value='<?php echo $this->wantmask['meet']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['meet']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='meet'><?php echo JText::_('WANTTO_MEET'); ?></label></td>
			<td><input type='checkbox' name='wantmask[]' id='behired' value='<?php echo $this->wantmask['behired']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['behired']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='behired'><?php echo JText::_('WANTTO_BEHIRED'); ?></label></td>
		</tr>
		<tr>
			<td><input type='checkbox' name='wantmask[]' id='group' value='<?php echo $this->wantmask['group']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['group']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='group'><?php echo JText::_('WANTTO_GROUP'); ?></label></td>
			<td><input type='checkbox' name='wantmask[]' id='buy' value='<?php echo $this->wantmask['buy']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['buy']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='buy'><?php echo JText::_('WANTTO_BUY'); ?></label></td>
		</tr>
		<tr>
			<td><input type='checkbox' name='wantmask[]' id='sell' value='<?php echo $this->wantmask['sell']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['sell']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='sell'><?php echo JText::_('WANTTO_SELL'); ?></label></td>
			<td><input type='checkbox' name='wantmask[]' id='hire' value='<?php echo $this->wantmask['hire']; ?>'<?php echo ($this->profiledata['wantmask'] & $this->wantmask['hire']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='hire'><?php echo JText::_('WANTTO_HIRE'); ?></label></td>
		</tr>
		<tr>
			<td colspan='4'><input type='text' size='50' name='wanttext' id='wanttext' value='<?php echo $this->profiledata['wanttext']; ?>' /></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label><?php echo JText::_('PROFILE_SKILLS'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'>
		<table class='wanttotable'>
		<tr>
			<td><input type='checkbox' name='skillsmask[]' id='textures' value='<?php echo $this->skillsmask['textures']; ?>'<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['textures']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='textures'><?php echo JText::_('SKILLS_TEXTURES'); ?></label></td>
			<td><input type='checkbox' name='skillsmask[]' id='architecture' value='<?php echo $this->skillsmask['architecture']; ?>'<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['architecture']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='architecture'><?php echo JText::_('SKILLS_ARCHITECTURE'); ?></label></td>
		</tr>
		<tr>
			<td><input type='checkbox' name='skillsmask[]' id='modeling' value='<?php echo $this->skillsmask['modeling']; ?>'<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['modeling']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='modeling'><?php echo JText::_('SKILLS_MODELING'); ?></label></td>
			<td><input type='checkbox' name='skillsmask[]' id='eventplanning' value='<?php echo $this->skillsmask['eventplanning']; ?>'<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['eventplanning']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='eventplanning'><?php echo JText::_('SKILLS_EVENTPLANNING'); ?></label></td>
		</tr>
		<tr>
			<td><input type='checkbox' name='skillsmask[]' id='scripting' value='<?php echo $this->skillsmask['scripting']; ?>'<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['scripting']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='scripting'><?php echo JText::_('SKILLS_SCRIPTING'); ?></label></td>
			<td><input type='checkbox' name='skillsmask[]' id='customcharacters' value='<?php echo $this->skillsmask['customcharacters']; ?>'<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['customcharacters']) ? " checked='checked'":""; ?> /></td>
			<td width='45%'><label for='customcharacters'><?php echo JText::_('SKILLS_CUSTOMCHARACTERS'); ?></label></td>
		</tr>
		<tr>
			<td colspan='4'><input type='text' size='50' name='skillstext' id='skillstext' value='<?php echo $this->profiledata['skillstext']; ?>' /></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label for='languages'><?php echo JText::_('PROFILE_LANGUAGES'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><input type='text' size='50' name='languages' id='languages' value='<?php echo $this->profiledata['languages']; ?>' /></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><label for='aboutText'><?php echo JText::_('PROFILE_REALLIFE'); ?>:</label></td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><textarea name='firstLifeText' id='firstLifeText' rows='5' cols='40'><?php echo $this->profiledata['firstLifeText']; ?></textarea></td>
	</tr>
	<tr class='<?php echo $this->pageclass_sfx; ?>'>
		<td colspan='2' class='<?php echo $this->pageclass_sfx; ?>'>
		<input type='submit' class='button' value='<?php echo JText::_('SAVECHANGES'); ?>' class='<?php echo $this->pageclass_sfx; ?>' />
		</td>
	</tr>
	</table>
	</form>
	</td>
</tr>
</table>
<?php endif; ?>