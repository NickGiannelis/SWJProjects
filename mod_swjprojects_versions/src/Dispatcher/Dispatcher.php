<?php
/**
 * @package    SW JProjects
 * @version    2.2.0
 * @author     Sergey Tolkachyov
 * @сopyright  Copyright (c) 2018 - 2025 Sergey Tolkachyov. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link       https://web-tolk.ru
 */

namespace Joomla\Module\Swjprojectsversions\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\Module\Swjprojectsversions\Site\Helper\SwjprojectsversionsHelper;
use function defined;

defined('JPATH_PLATFORM') or die;
/**
 * Dispatcher class for mod_swjprojects_versions
 *
 * @since  1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher
{

	/**
	 * Returns the layout data.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	protected function getLayoutData()
	{
		$data = parent::getLayoutData();
		$helper = new SwjprojectsversionsHelper();
		$data['items'] = $helper->getVersions($data['params'], $this->getApplication());

		return $data;
	}
}