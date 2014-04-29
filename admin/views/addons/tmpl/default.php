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
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="addons" />
<input type="hidden" name="task" value="" />
</form>
<div class='jopensim_addon_table'>
<div class='jopensim_addon_tr'>
<div class='jopensim_addon_td1'>
<?php if(($this->addons & 1) == 1): ?>
<p><span class='addonsubtitle'><?php echo JText::_('ADDONS_MESSAGES'); ?>&nbsp;<a href='index.php?option=com_opensim&view=addons&task=ominfo'><img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='<?php echo JText::_('JOPENSIM_ADDONS_MESSAGES_INFO'); ?>' alt='<?php echo JText::_('JOPENSIM_ADDONS_MESSAGES_INFO'); ?>' /></a></span></p>
<?php endif; ?>

<?php if(($this->addons & 2) == 2): ?>
<p><span class='addonsubtitle'><?php echo JText::_('ADDONS_PROFILE'); ?>&nbsp;<a href='index.php?option=com_opensim&view=addons&task=pinfo'><img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='<?php echo JText::_('JOPENSIM_ADDONS_PROFILE_INFO'); ?>' alt='<?php echo JText::_('JOPENSIM_ADDONS_PROFILE_INFO'); ?>' /></a></span></p>
<?php endif; ?>

<?php if(($this->addons & 4) == 4): ?>
<p><span class='addonsubtitle'><a href='index.php?option=com_opensim&view=groups'><?php echo JText::_('ADDONS_GROUPS'); ?></a>&nbsp;<a href='index.php?option=com_opensim&view=addons&task=ginfo'><img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='<?php echo JText::_('JOPENSIM_ADDONS_GROUPS_INFO'); ?>' alt='<?php echo JText::_('JOPENSIM_ADDONS_GROUPS_INFO'); ?>' /></a></span></p>
<?php endif; ?>

<?php if(($this->addons & 16) == 16): ?>
<p><span class='addonsubtitle'><?php echo JText::_('ADDONS_SEARCH'); ?>&nbsp;<a href='index.php?option=com_opensim&view=addons&task=sinfo'><img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='<?php echo JText::_('JOPENSIM_ADDONS_SEARCH_INFO'); ?>' alt='<?php echo JText::_('JOPENSIM_ADDONS_SEARCH_INFO'); ?>' /></a></span></p>
<?php endif; ?>

<?php if(($this->addons & 32) == 32): ?>
<p><span class='addonsubtitle'><?php echo JText::_('JOPENSIM_MONEY'); ?>&nbsp;<a href='index.php?option=com_opensim&view=addons&task=minfo'><img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='<?php echo JText::_('JOPENSIM_ADDONS_MONEY_INFO'); ?>' alt='<?php echo JText::_('JOPENSIM_ADDONS_MONEY_INFO'); ?>' /></a></span></p>
<?php endif; ?>

<?php if(($this->addons & 8) == 8): ?>
<p><span class='addonsubtitle'><?php echo JText::_('JOPENSIM_ADDONS_INWORLD_IDENT'); ?>&nbsp;<a href='index.php?option=com_opensim&view=addons&task=iaiinfo'><img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='<?php echo JText::_('JOPENSIM_ADDONS_IAI_INFO'); ?>' alt='<?php echo JText::_('JOPENSIM_ADDONS_IAI_INFO'); ?>' /></a></span></p>
<?php endif; ?>
</div>
<div class='jopensim_addon_td2'>
<pre class='jopensim_addoninfo'>
<?php echo $this->infotext; ?>
</pre>
</div>
</div>
</div>
