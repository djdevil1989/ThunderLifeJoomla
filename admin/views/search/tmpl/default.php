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
if(is_array($this->searchoptions) && count($this->searchoptions) > 0) {
?>
<script type='text/javascript'>
function selectAll(top) {
    var i;
    for(i=0;i<top.options.length;i++) top.options[i].selected=true;
}

function moveTopUp(top) {
    var i, opt;
    for(i=1;i<top.options.length;i++) {
        if(top.options[i].selected && !top.options[i-1].selected) {
            opt=cloneOption(top.options[i]);
            top.options[i]=cloneOption(top.options[i-1]);
            top.options[i-1]=opt;
        }
    }
}

function moveTopDown(top) {
    var i, opt;
    for(i=top.options.length-2;i>=0;i--) {
        if(top.options[i].selected && !top.options[i+1].selected) {
            opt=cloneOption(top.options[i]);
            top.options[i]=cloneOption(top.options[i+1]);
            top.options[i+1]=opt;
        }
    }
    return false;
}
function cloneOption(opt) {
    fromStyle = opt.className;
    fromTitle = opt.title;
    newOpt = new Option(opt.text, opt.value, opt.defaultSelected, opt.selected);
    newOpt.className = fromStyle;
    newOpt.title = fromTitle;
    return newOpt;
}

</script>
<form action="index.php" method="post" name="adminForm" onSubmit='selectAll(document.adminForm.sortsearchoptions)'>
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="search" />
<input type="hidden" name="task" value="" />
<table class='jopensim_search_settings' cellspacing='10'>
<tr>
	<td colspan='3'><?php echo JText::_('JOPENSIM_SEARCH_SETTINGS_DESC'); ?></td>
</tr>
<tr>
	<td colspan='2'><?php echo JText::_('JOPENSIM_SEARCH_SETTINGS_ENABLE_DESC'); ?></td>
	<td class='jopensim_search_settings_td2'><?php echo JText::_('JOPENSIM_SEARCH_SETTINGS_SORT_DESC'); ?></td>
</tr>
<tr>
	<td width='10' align='center'><input type='checkbox' id='searchoption1' name='searchoptions[]' value='JOPENSIM_SEARCH_OBJECTS' <?php echo ($this->searchoptions['JOPENSIM_SEARCH_OBJECTS']['enabled'] == 1) ? " checked='checked'":""; ?>/></td>
	<td><label for='searchoption1'><?php echo JText::_('JOPENSIM_SEARCH_OBJECTS'); ?></label></td>
	<td rowspan='5' class='jopensim_search_settings_td2' align='center' valign='top'>
	<table>
	<tr>
		<td>
		<select name='sortsearchoptions[]' id='sortsearchoptions' size='<?php echo count($this->searchsort); ?>' multiple='multiple'>
		<?php foreach($this->searchsort AS $key => $option): ?>
			<option value='<?php echo $key; ?>'><?php echo $option['name']; ?></option>
		<?php endforeach; ?>
		</select>
		</td>
		<td>
		<img src='<?php echo JURI::base(true); ?>/components/com_opensim/assets/images/up.gif' width='17' height='20' border='0' onClick="moveTopUp(document.getElementById('sortsearchoptions'));return false;" title='move up' alt='move up' /><br />
		<img src='<?php echo JURI::base(true); ?>/components/com_opensim/assets/images/down.gif' width='17' height='20' border='0' onClick="moveTopDown(document.getElementById('sortsearchoptions'));return false;" title='move down' alt='move down' />
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td width='10' align='center'><input type='checkbox' id='searchoption2' name='searchoptions[]' value='JOPENSIM_SEARCH_PARCELS' <?php echo ($this->searchoptions['JOPENSIM_SEARCH_PARCELS']['enabled'] == 1) ? " checked='checked'":""; ?>/></td>
	<td><label for='searchoption2'><?php echo JText::_('JOPENSIM_SEARCH_PARCELS'); ?></label></td>
</tr>
<tr>
	<td width='10' align='center'><input type='checkbox' id='searchoption3' name='searchoptions[]' value='JOPENSIM_SEARCH_PARCELSALES' <?php echo ($this->searchoptions['JOPENSIM_SEARCH_PARCELSALES']['enabled'] == 1) ? " checked='checked'":""; ?>/></td>
	<td><label for='searchoption3'><?php echo JText::_('JOPENSIM_SEARCH_PARCELSALES'); ?></label></td>
</tr>
<tr>
	<td width='10' align='center'><input type='checkbox' id='searchoption4' name='searchoptions[]' value='JOPENSIM_SEARCH_POPULARPLACES' <?php echo ($this->searchoptions['JOPENSIM_SEARCH_POPULARPLACES']['enabled'] == 1) ? " checked='checked'":""; ?>/></td>
	<td><label for='searchoption4'><?php echo JText::_('JOPENSIM_SEARCH_POPULARPLACES'); ?></label></td>
</tr>
<tr>
	<td width='10' align='center'><input type='checkbox' id='searchoption5' name='searchoptions[]' value='JOPENSIM_SEARCH_REGIONS' <?php echo ($this->searchoptions['JOPENSIM_SEARCH_REGIONS']['enabled'] == 1) ? " checked='checked'":""; ?>/></td>
	<td><label for='searchoption5'><?php echo JText::_('JOPENSIM_SEARCH_REGIONS'); ?></label></td>
</tr>
</table>
</form>
<!--
<pre>
<?php print_r($this->searchoptions); ?>
</pre>
-->
<?php
} else {
	echo JText::_('JOPENSIM_SEARCH_OPTIONERROR');
}