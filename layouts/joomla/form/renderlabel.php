<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

extract($displayData);

/**
 * Layout variables
 * ---------------------
 * 	$text         : (string)  The label text
 * 	$for          : (string)  The id of the input this label is for
 * 	$required     : (boolean) True if a required field
 * 	$classes      : (array)   A list of classes
 */

$classes = array_filter((array) $classes);

$sronly = strtolower($displayData['field']->type) === 'editor' ? 'sr-only' : '';

$id    = $for . '-lbl';

if ($required)
{
	$classes[] = 'required';
}

if (!empty($sronly))
{
	$classes[] = $sronly;
}

if(!isset($displayData['field']->skipLabelFor) || $displayData['field']->skipLabelFor !== true)
{
	$forAttr = 'for="' . $for . '"';
}
?>
<label id="<?php echo $id; ?>" for="<?php echo $for; ?>"<?php if (!empty($classes)) { echo ' class="' . implode(' ', $classes) . '"';} ?>>
	<?php echo $text; ?><?php if ($required) : ?><span class="star" aria-hidden="true">&#160;*</span><?php endif; ?>
</label>
