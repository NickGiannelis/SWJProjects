<?php
/*
 * @package    SW JProjects Component
 * @version    1.7.0
 * @author Septdir Workshop, <https://septdir.com>, Sergey Tolkachyov <https://web-tolk.ru>
 * @сopyright (c) 2018 - August 2023 Septdir Workshop, Sergey Tolkachyov. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link https://septdir.com, https://web-tolk.ru
 */

defined('_JEXEC') or die;

use Joomla\CMS\Version;

echo $this->loadTemplate(((new Version())->isCompatible('4.0')) ? 'j4' : 'j3');