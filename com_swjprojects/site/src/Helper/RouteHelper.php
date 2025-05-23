<?php
/**
 * @package       SW JProjects
 * @version       2.4.0.1
 * @Author        Sergey Tolkachyov
 * @copyright     Copyright (c) 2018 - 2025 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://web-tolk.ru
 * @since         1.0.0
 */

namespace Joomla\Component\SWJProjects\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\RouteHelper as CMSRouteHelper;
use function defined;

class RouteHelper extends CMSRouteHelper
{
	/**
	 * Fetches jupdate route.
	 *
	 * @param   int     $project_id    The id of the project.
	 * @param   string  $element       The element of the project.
	 * @param   string  $download_key  The download key value.
	 *
	 * @return  string  Joomla update server view link.
	 *
	 * @since  1.0.0
	 */
	public static function getJUpdateRoute($project_id = null, $element = null, ?string $download_key = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=jupdate';

		if (!empty($project_id))
		{
			$link .= '&project_id=' . $project_id;
		}

		if (!empty($element))
		{
			$link .= '&element=' . $element;
		}

		if (!empty($download_key))
		{
			$link .= '&download_key=' . $download_key;
		}

		return $link;
	}

	/**
	 * Fetches jupdate route.
	 *
	 * @param   int     $project_id  The id of the project.
	 * @param   string  $element     The element of the project.
	 *
	 * @return  string  Joomla changelog server view link.
	 *
	 * @since  1.7.0
	 */
	public static function getJChangelogRoute($project_id = null, $element = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=jchangelog';

		if (!empty($project_id))
		{
			$link .= '&project_id=' . $project_id;
		}

		if (!empty($element))
		{
			$link .= '&element=' . $element;
		}

		return $link;
	}

	/**
	 * Fetches download route.
	 *
	 * @param   int     $version_id    The id of the version.
	 * @param   int     $project_id    The id of the project.
	 * @param   string  $element       The element of the project.
	 * @param   string  $download_key  The download key value.
	 *
	 * @return  string  Download link.
	 *
	 * @since  1.0.0
	 */
	public static function getDownloadRoute($version_id = null, $project_id = null, $element = null, $download_key = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=download';

		if (!empty($version_id))
		{
			$link .= '&version_id=' . $version_id;
		}

		if (!empty($project_id))
		{
			$link .= '&project_id=' . $project_id;
		}

		if (!empty($element))
		{
			$link .= '&element=' . $element;
		}

		if (!empty($download_key))
		{
			$link .= '&download_key=' . $download_key;
		}

		return $link;
	}

	/**
	 * Fetches version route.
	 *
	 * @param   int  $id          The id of the version.
	 * @param   int  $project_id  The id of the project.
	 * @param   int  $catid       The id of the category.
	 *
	 * @return  string  Version view link.
	 *
	 * @since  1.0.0
	 */
	public static function getVersionRoute($id = null, $project_id = null, $catid = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=version';

		if (!empty($id))
		{
			$link .= '&id=' . $id;
		}

		if (!empty($project_id))
		{
			$link .= '&project_id=' . $project_id;
		}

		if (!empty($catid))
		{
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Fetches versions route.
	 *
	 * @param   int  $id     The id of the project.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string  Versions view link.
	 *
	 * @since  1.0.0
	 */
	public static function getVersionsRoute($id = null, $catid = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=versions';

		if (!empty($id))
		{
			$link .= '&id=' . $id;
		}

		if (!empty($catid))
		{
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Fetches document route.
	 *
	 * @param   int  $id          The id of the version.
	 * @param   int  $project_id  The id of the project.
	 * @param   int  $catid       The id of the category.
	 *
	 * @return  string  Document view link.
	 *
	 * @since  1.4.0
	 */
	public static function getDocumentRoute($id = null, $project_id = null, $catid = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=document';

		if (!empty($id))
		{
			$link .= '&id=' . $id;
		}

		if (!empty($project_id))
		{
			$link .= '&project_id=' . $project_id;
		}

		if (!empty($catid))
		{
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Fetches documentation route.
	 *
	 * @param   int  $id     The id of the project.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string  Documentation view link.
	 *
	 * @since  1.4.0
	 */
	public static function getDocumentationRoute($id = null, $catid = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=documentation';

		if (!empty($id))
		{
			$link .= '&id=' . $id;
		}

		if (!empty($catid))
		{
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Fetches project route.
	 *
	 * @param   int  $id     The id of the project.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string  Project view link.
	 *
	 * @since  1.0.0
	 */
	public static function getProjectRoute($id = null, $catid = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=project';

		if (!empty($id))
		{
			$link .= '&id=' . $id;
		}

		if (!empty($catid))
		{
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Fetches projects route.
	 *
	 * @param   int  $id  The id of the category.
	 *
	 * @return  string  Projects view link.
	 *
	 * @since  1.0.0
	 */
	public static function getProjectsRoute($id = null): string
	{
		$link = 'index.php?option=com_swjprojects&view=projects';

		if (!empty($id))
		{
			$link .= '&id=' . $id;
		}

		return $link;
	}

	/**
	 * Fetches user keys list route.
	 *
	 *
	 * @return  string  User keys view link.
	 *
	 * @since  2.3.0
	 */
	public static function getUserkeysRoute(): string
	{
		return 'index.php?option=com_swjprojects&view=userkeys';
	}
}
