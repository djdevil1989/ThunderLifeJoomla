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
<h1><?php echo $this->ueberschrift; ?></h1>
<p><a href='index.php?option=com_opensim&view=maps&task=mapview'><?php echo JText::_('JOPENSIM_VIEW_MAP_GRID'); ?></a>
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='maps' />
<input type='hidden' name='task' value='' />
<input type='hidden' name='boxchecked' value='0' />
<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
<?php if(is_array($this->regions)): ?>
<?php echo JText::_('SELECT_MAP'); ?><br /><br />
<table>
<tr>
	<td width="100%">
	<?php echo JText::_( 'Filter' ); ?>:
	<input type="text" name="search" id="search" value="<?php echo $this->filter;?>" class="text_area" onchange="document.adminForm.submit();" />
	<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
	<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
	</td>
</tr>
</table>
<table class="adminlist" cellspacing="1">
<colgroup>
	<col width='10'>
	<col width='10'>
	<col width='200'>
	<col width='200'>
	<col width='10'>
	<col width='64'>
	<col width='10'>
	<col width='10'>
	<col width='10'>
	<col width='10'>
	<col width='*'>
</colgroup>
<thead>
<tr>
	<th width="5"><?php echo JText::_('Num'); ?></th>
	<th>&nbsp;</th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('REGION_NAME'), 'regions.regionName', $this->sortDirection, $this->sortColumn); ?></th>
<!--
	<th><?php echo JText::_('REGION_NAME'); ?></th>
-->
	<th class='title'><?php echo JText::_('REGION_OWNER'); ?></th>
	<th class='title'><?php echo JText::_('REGION_DEFAULT'); ?></th>
	<th class='title'>&nbsp;</th>
	<th class='title'><?php echo JText::_('REGION_VISIBLE'); ?></th>
	<th class='title'><nobr><?php echo JHTML::_( 'grid.sort', JText::_('JOPENSIM_REGION_POSITION_X'), 'regions.locX', $this->sortDirection, $this->sortColumn); ?></nobr></th>
	<th class='title'><nobr><?php echo JHTML::_( 'grid.sort', JText::_('JOPENSIM_REGION_POSITION_Y'), 'regions.locY', $this->sortDirection, $this->sortColumn); ?></nobr></th>
<!--
	<th class='title'><nobr><?php echo JText::_('JOPENSIM_REGION_POSITION_X'); ?></nobr></th>
	<th class='title'><nobr><?php echo JText::_('JOPENSIM_REGION_POSITION_Y'); ?></nobr></th>
-->
	<th class='title'><?php echo JText::_('REGION_ARTICLE'); ?></th>
	<th class='title'>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php
$k = 0;
$i=0;
foreach($this->regions AS $key => $region) {
?>
<tr class="row<?php echo $i % 2; ?>">
	<td><?php echo $this->pagination->getRowOffset($i); ?></td>
	<td><input type='radio' name='selectedRegion' id='selectedRegion_<?php echo $key; ?>' value='<?php echo $region['uuid']; ?>' onClick='isChecked(this.checked);' /></td>
	<td><label for='selectedRegion_<?php echo $key; ?>'><nobr><a href='index.php?option=com_opensim&view=maps&task=edit&selectedRegion=<?php echo $region['uuid']; ?>'><?php echo $region['regionName']; ?></a></nobr></label></td>
	<td><nobr><?php echo $region['ownername']; ?></nobr></td>
	<td align='center'>
	<a href='index.php?option=com_opensim&view=maps&task=selectdefault&region=<?php echo $region['uuid']; ?>'>
	<?php if($this->settings['defaulthome'] == $region['uuid']): ?>
	<span class="jgrid" title="<?php echo JText::_('JDEFAULT'); ?>"><span class="state default"><span class="text"><?php echo JText::_('JDEFAULT'); ?></span></span></span>
	<?php else: ?>
	<span class="jgrid" title="<?php echo JText::_('JLIB_HTML_SETDEFAULT_ITEM'); ?>"><span class="state notdefault"><span class="text"><?php echo JText::_('JLIB_HTML_SETDEFAULT_ITEM'); ?></span></span></span>
	<?php endif; ?>
	</a>
	</td>
	<td><?php echo $region['image']; ?></td>
	<td align='center' class='jgrid'><a href='index.php?option=com_opensim&view=maps&task=<?php echo ($region['hidemap'] == 1) ? "setRegionVisible":"setRegionInvisible"; ?>&region=<?php echo $region['uuid']; ?>' title='<?php echo JText::_('REGION_TOGGLE_VISIBILITY'); ?>'><span class='state <?php echo ($region['hidemap'] == 1) ? "unpublish":"publish"; ?>'><span class='text'><?php echo JText::_('REGION_TOGGLE_VISIBILITY'); ?></span></a></span></td>
	</td>
	<td><?php echo $region['posX']; ?></td>
	<td><?php echo $region['posY']; ?></td>
	<td><nobr><?php echo ($region['articleId']) ? "<a href='index.php?option=com_content&task=edit&cid[]=".$region['articleId']."'>".$region['articleTitle']."</a>":JText::_('JNONE'); ?></nobr></td>
	<td>&nbsp;</td>
</tr>
<?php
	$i++;
	$k = 1 - $k;
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan='11'><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
</tfoot>
</table>
<?php else: ?>
<?php echo JText::_('ERROR_NOREGION'); ?> for Maps!<br />
<?php echo JText::_('ERRORQUESTION1'); ?><br />
<?php echo JText::_('ERRORQUESTION2'); ?><br />
<?php if(isset($this->os_setting['errormsg'])): ?>
<br />
<?php echo JText::_('ERRORMSG').": ".$this->os_setting['errormsg']; ?>
<?php endif; ?>
<?php endif; ?>
</form>
