<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class JElementOpenSimHead extends JElement {
	var	$_name = 'OpenSim';

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '&nbsp;';
	}

	function fetchElement($name, $value, &$node, $control_name) {
		if ($value) {
			return '<p style="background: #236D2D;color: #efefef;padding:5px"><strong>' . JText::_($value) . '</strong></p>';
		} else {
			return '<hr />';
		}
	}
}

class JElementOpenSimSubHead extends JElement {
	var	$_name = 'OpenSim';


	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		$retval = JHTML::tooltip(JText::_($description),JText::_($label));
		return $retval;
	}


	function fetchElement($name, $value, &$node, $control_name) {
		if ($value) {
			return '<p style="background: #9FCCA3;color: #000000;padding:5px"><strong>' . JText::_($value) . '</strong></p>';
		} else {
			return '<hr />';
		}
	}
}

class JElementMultiList2 extends JElement
{
        /**
        * Element name
        *
        * @access       protected
        * @var          string
        */
        var    $_name = 'MultiList';
 
        function fetchElement($name, $value, &$node, $control_name)
        {
                // Base name of the HTML control.
                $ctrl  = $control_name .'['. $name .']';
 
                // Construct an array of the HTML OPTION statements.
                $options = array ();
                foreach ($node->children() as $option)
                {
                        $val   = $option->attributes('value');
                        $text  = $option->data();
                        $options[] = JHTML::_('select.option', $val, JText::_($text));
                }
 
                // Construct the various argument calls that are supported.
                $attribs       = ' ';
                if ($v = $node->attributes( 'size' )) {
                        $attribs       .= 'size="'.$v.'"';
                }
                if ($v = $node->attributes( 'class' )) {
                        $attribs       .= 'class="'.$v.'"';
                } else {
                        $attribs       .= 'class="inputbox"';
                }
                if ($m = $node->attributes( 'multiple' ))
                {
                        $attribs       .= ' multiple="multiple"';
                        $ctrl          .= '[]';
                }
 
                // Render the HTML SELECT list.
                return JHTML::_('select.genericlist', $options, $ctrl, $attribs, 'value', 'text', $value, $control_name.$name );
        }
}?>