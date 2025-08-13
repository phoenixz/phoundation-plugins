<?php

/**
 * Updates class
 *
 * This is the Init class for the Bookmarks library
 *
 * @see \Phoundation\Core\Libraries\Updates
 * @author    Sven Olaf Oostenbrink <sven@medinet.ca>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Bookmarks
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Bookmarks\Library;


class Updates extends \Phoundation\Core\Libraries\Updates
{
    /**
     * The current version for this library
     *
     * @return string
     */
    public function version(): string
    {
        return '0.8.0';
    }


    /**
     * The description for this library
     *
     * @return string
     */
    public function description(): string
    {
        return tr('This library contains Bookmarks specific functionalities');
    }


    /**
     * The list of version updates available for this library
     *
     * @return void
     */
    public function updates(): void
    {
        $this->addUpdate('0.3.0', function () {
            // Drop the tables to be sure we have a clean slate
            sql()->getSchemaObject()->getTableObject('bookmarks')->drop();

            // Create the health authorities table.
            sql()->getSchemaObject()->getTableObject('bookmarks')->define()
                ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `created_by` bigint DEFAULT NULL,
                    `meta_id` bigint NULL DEFAULT NULL,
                    `meta_state` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `status` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `users_id` bigint NOT NULL DEFAULT 50,
                    `priority` int NOT NULL DEFAULT 50,
                    `top_level` tinyint NOT NULL DEFAULT 50,
                    `name` varchar(128) DEFAULT NULL,
                    `seo_name` varchar(128) DEFAULT NULL,
                    `url` varchar(2040) DEFAULT NULL,
                    `description` text DEFAULT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    UNIQUE `seo_name` (`seo_name`),
                    KEY `created_on` (`created_on`),
                    KEY `created_by` (`created_by`),
                    KEY `status` (`status`),
                    KEY `users_id` (`users_id`),
                    KEY `priority` (`priority`),
                    KEY `top_level` (`top_level`),
                    UNIQUE KEY `users_id_seo_name` (`users_id`, `seo_name`),
                    KEY `users_id_priority` (`users_id`, `priority`),
                    KEY `users_id_top_level` (`users_id`, `top_level`),
                    KEY `name` (`name`),
                ')->setForeignKeys('
                    CONSTRAINT `fk_bookmarks_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_bookmarks_meta_id` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
                    CONSTRAINT `fk_bookmarks_users_id` FOREIGN KEY (`users_id`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                ')->create();

        })->addUpdate('0.8.0', function () {
            // Add support for modified_on and modified_by
            $this->ensureModifiedColumns([
                'bookmarks',
            ]);
        });
    }
}
