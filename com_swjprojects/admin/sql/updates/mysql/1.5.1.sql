/*
 * @package       SW JProjects
 * @version    2.4.0
 * @author     Sergey Tolkachyov
 * @copyright  Copyright (c) 2018 - 2025 Sergey Tolkachyov. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link       https://web-tolk.ru
 */

ALTER TABLE `#__swjprojects_keys` ADD `limit` TINYINT(3) NOT NULL DEFAULT 0 AFTER `date_end`;
ALTER TABLE `#__swjprojects_keys` ADD `limit_count` INT(11) NOT NULL DEFAULT 0 AFTER `limit`;