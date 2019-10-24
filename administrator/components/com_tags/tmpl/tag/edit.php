<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tabstate');

HTMLHelper::_('script', 'com_contenthistory/admin-history-versions.js', ['version' => 'auto', 'relative' => true]);

// Fieldsets to not automatically render by /layouts/joomla/edit/params.php
$this->ignore_fieldsets = ['jmetadata'];
$this->useCoreUI = true;

?>

<form action="<?php echo Route::_('index.php?option=com_tags&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div>
		<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_TAGS_FIELDSET_DETAILS')); ?>
		<div class="row">
			<div class="col-lg-9">
				<?php echo $this->form->getLabel('description'); ?>
				<?php echo $this->form->getInput('description'); ?>
			</div>
			<div class="col-lg-3">
				<div class="j-card">
					<div class="j-card-body">
						<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
		<div class="row">
			<div class="col-12 col-lg-6">
				<fieldset id="fieldset-publishingdata" class="options-grid-form options-grid-form-full">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<div>
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
				</fieldset>
			</div>
			<div class="col-md-6">
				<div class="sr-only"><?php echo Text::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'); ?></div>
				<div>
					<?php echo LayoutHelper::render('joomla.edit.metadata', $this); ?>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
