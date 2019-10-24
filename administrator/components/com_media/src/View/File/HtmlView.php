<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Media\Administrator\View\File;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit an file.
 *
 * @since  4.0.0
 */
class HtmlView extends BaseHtmlView
{

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   4.0.0
	 */
	public function display($tpl = null)
	{
		$input = Factory::getApplication()->input;

		$this->form = $this->get('Form');

		// The component params
		$this->params = ComponentHelper::getParams('com_media');

		// The requested file
		$this->file = $this->getModel()->getFileInformation($input->getString('path', null));

		if (empty($this->file->content))
		{
			// @todo error handling controller redirect files
			throw new \Exception('No content available!');
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the toolbar buttons
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_MEDIA_EDIT'), 'media mediamanager');

		ToolbarHelper::apply('apply');
		ToolbarHelper::save('save');
		ToolbarHelper::custom('reset', 'refresh', '',  'COM_MEDIA_RESET', false);

		ToolbarHelper::cancel('cancel');
	}
}
