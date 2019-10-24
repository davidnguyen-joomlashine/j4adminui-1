<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
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

?>

<form action="<?php echo Route::_('index.php?option=com_users&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="level-form" class="form-validate">
	
	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="j-card">
		<div class="j-card-header">
			<?php echo Text::_('COM_USERS_USER_GROUPS_HAVING_ACCESS'); ?>
		</div>
		<div class="j-card-body">
			<div class="form-no-margin">
				<?php echo HTMLHelper::_('access.usergroups', 'jform[rules]', $this->item->rules, true); ?>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
