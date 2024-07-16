<?php

/**
 * Updates class
 *
 * This is the Init class for the Statistics library
 *
 * @see \Phoundation\Core\Libraries\Updates
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Statistics
 */

declare(strict_types=1);

namespace Plugins\Phoundation\Statistics\Library;



class Updates extends \Phoundation\Core\Libraries\Updates
{
    /**
     * The current version for this library
     *
     * @return string
     */
    public function version(): string
    {
        return '0.0.12';
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
            sql()->getSchemaObject()->getTableObject('statistics_queue')->drop();
            sql()->getSchemaObject()->getTableObject('statistics_servers')->drop();

            // Create the statistics_queue table.
            sql()->getSchemaObject()->getTableObject('statistics_servers')->define()
                ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `created_by` bigint DEFAULT NULL,
                    `meta_id` bigint NULL DEFAULT NULL,
                    `meta_state` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `status` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `name` varchar(128) DEFAULT NULL,
                    `seo_name` varchar(128) DEFAULT NULL,
                    `provider` enum("grafana"),
                    `host` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
                    `port` int(11) DEFAULT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    KEY `created_on` (`created_on`),
                    KEY `created_by` (`created_by`),
                    KEY `status` (`status`),
                    KEY `meta_id` (`meta_id`),
                    UNIQUE KEY `seo_name` (`seo_name`),
                    UNIQUE KEY `name` (`name`),
                ')->setForeignKeys('
                    CONSTRAINT `fk_statistics_servers_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_statistics_servers_meta_id` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
                ')->create();

            // Create the statistics_queue table.
            sql()->getSchemaObject()->getTableObject('statistics_queue')->define()
                ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `server` varchar(128) NOT NULL,
                    `path` varchar(255) DEFAULT NULL,
                    `value` decimal(20,5) NOT NULL,
                    `timestamp` bigint NOT NULL,
                    `clear_after` datetime NULL DEFAULT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `server` (`server`,`path`,`timestamp`),
                ')->create();
        });
    }
}
