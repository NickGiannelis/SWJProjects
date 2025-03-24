<?php
/**
 * @package       SW JProjects
 * @version       2.4.0
 * @Author        Sergey Tolkachyov
 * @copyright     Copyright (c) 2018 - 2025 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://web-tolk.ru
 * @since         1.0.0
 */

namespace Joomla\Component\SWJProjects\Administrator\Controller;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Component\SWJProjects\Administrator\Model\DocumentModel;
use function defined;

defined('_JEXEC') or die;

class DocumentationController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var  string
	 *
	 * @since  1.4.0
	 */
	protected $text_prefix = 'COM_SWJPROJECTS_DOCUMENTATION';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name.
	 * @param   string  $prefix  The class prefix.
	 * @param   array   $config  The array of possible config values.
	 *
	 * @return  DocumentModel  A model object.
	 *
	 * @since  1.4.0
	 */
	public function getModel($name = 'Document', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}
}