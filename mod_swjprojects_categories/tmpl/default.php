<?php
/**
 * @package    SW JProjects - Categories Module
 * @version    1.6.5
 * @author Septdir Workshop, <https://septdir.com>, Sergey Tolkachyov <https://web-tolk.ru>
 * @сopyright (c) 2018 - August 2023 Septdir Workshop, Sergey Tolkachyov. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link https://septdir.com, https://web-tolk.ru
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

?>
<ul class="categoriesList">
    <?php foreach ($items as $item) : ?>
        <li>
            <?php
                echo HTMLHelper::link(
                    SWJProjectsHelperRoute::getProjectsRoute($item->id), // URL
                    $item->title, // Link text
                    [] // attribs, like 'class' => 'btn btn-danger' or 'data-category-id' => $item->id
                );
            ?>
        </li>
    <?php endforeach; ?>
</ul>