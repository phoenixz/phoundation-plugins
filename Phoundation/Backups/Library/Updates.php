<?php

/**
 * Updates class
 *
 * This is the Init class for the Backups library
 *
 * @see \Phoundation\Core\Libraries\Updates
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Backups
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Backups\Library;



class Updates extends \Phoundation\Core\Libraries\Updates
{
    /**
     * The current version for this library
     *
     * @return string
     */
    public function version(): string
    {
        return '0.0.14';
    }


    /**
     * The list of version updates available for this library
     *
     * @return void
     */
    public function updates(): void
    {
        $this->addUpdate('0.0.12', function () {
            // Drop the tables to be sure we have a clean slate
            sql()->getSchemaObject()->getTableObject('plugin_backups')->drop();

            // Create the backups table.
            sql()->getSchemaObject()->getTableObject('plugin_backups')->define()
                ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `created_by` bigint DEFAULT NULL,
                    `meta_id` bigint NULL DEFAULT NULL,
                    `meta_state` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `status` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `size` bigint DEFAULT NULL,
                    `file` varchar(511) DEFAULT NULL,
                    `system_files` tinyint(1) NOT NULL DEFAULT 0,
                    `data_files` tinyint(1) NOT NULL DEFAULT 0,
                    `database` tinyint(1) NOT NULL DEFAULT 0,
                    `comments` text DEFAULT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    UNIQUE `created_on` (`created_on`),
                    KEY `created_by` (`created_by`),
                    KEY `status` (`status`),
                    KEY `size` (`size`),
                    KEY `file` (`file`),
                    KEY `system_files` (`system_files`),
                    KEY `data_files` (`data_files`),
                    KEY `database` (`database`),
                ')->setForeignKeys('
                    CONSTRAINT `fk_plugin_backups_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_plugin_backups_meta_id` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
                ')->create();
        });
    }
}
