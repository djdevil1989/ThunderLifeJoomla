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
<h1><?php echo JText::_('JOPENSIM_PROFILE_DETAILS'); ?></h1>
<?php if($this->settingsdata['addons'] & 2): ?>
<table cellspacing='0' cellpadding='0'>
<tr>
	<td>
	<table class='jopensim_profiletable<?php echo $this->pageclass_sfx; ?>'>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('FIRST_NAME'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $this->profiledata['firstname']; ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('LAST_NAME'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $this->profiledata['lastname']; ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_ABOUTME'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo nl2br($this->profiledata['aboutText']); ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_PARTNER'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $this->profiledata['partnername']; ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_URL'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $this->profiledata['url']; ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' colspan='2' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_INTERESTS'); ?>:</td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_WANTTO'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'>
		<table class='jopensim_wanttotable' border='0'>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_BUILD'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['build']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['build']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['build']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_EXPLORE'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['explore']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['explore']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['explore']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_MEET'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['meet']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['meet']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['meet']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_BEHIRED'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['behired']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['behired']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['behired']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_GROUP'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['group']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['group']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['group']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_BUY'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['buy']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['buy']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['buy']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_SELL'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['sell']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['sell']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['sell']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('WANTTO_HIRE'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['wantmask'] & $this->wantmask['hire']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['hire']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['wantmask'] & $this->wantmask['hire']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td colspan='5'><?php echo $this->profiledata['wanttext']; ?></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_SKILLS'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'>
		<table class='wanttotable'>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('SKILLS_TEXTURES'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['textures']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['textures']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['textures']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('SKILLS_ARCHITECTURE'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['architecture']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['architecture']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['architecture']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('SKILLS_MODELING'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['modeling']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['modeling']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['modeling']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('SKILLS_EVENTPLANNING'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['eventplanning']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['eventplanning']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['eventplanning']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td class='jopensim_col1'><?php echo JText::_('SKILLS_SCRIPTING'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['scripting']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['scripting']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['scripting']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
			<td class='jopensim_col3'>&nbsp;</td>
			<td class='jopensim_col1'><?php echo JText::_('SKILLS_CUSTOMCHARACTERS'); ?></td>
			<td class='jopensim_col2'><span class='jopensim_profile_state <?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['customcharacters']) ? "jopensim_profile_yes":"jopensim_profile_no"; ?>'><img src='<?php echo JURI::root(); ?>components/com_opensim/assets/images/null.gif' width='16' height='16' alt='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['customcharacters']) ? JText::_('JYES'):JText::_('JNO'); ?>' title='<?php echo ($this->profiledata['skillsmask'] & $this->skillsmask['customcharacters']) ? JText::_('JYES'):JText::_('JNO'); ?>' /></span></td>
		</tr>
		<tr>
			<td colspan='5'><?php echo $this->profiledata['skillstext']; ?></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_LANGUAGES'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $this->profiledata['languages']; ?></td>
	</tr>
	<tr class='profiletable_row<?php echo $this->pageclass_sfx; ?>'>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('PROFILE_REALLIFE'); ?>:</td>
		<td valign='top' class='profiletable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo nl2br($this->profiledata['firstLifeText']); ?></td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?php endif; ?>
<?php
if($_REQUEST['uid'] == "643bb632-aaca-102f-9cd3-001c256a807b") {
	$test = var_export($_REQUEST['test']);
	echo "<pre>Test:\n\n".$test."</pre>\n";
}
?>