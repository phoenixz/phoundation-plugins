<?php

/**
 * Updates class
 *
 * This is the Init class for the Accounts library
 *
 * @see       \Phoundation\Core\Libraries\Updates
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Accounts
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Sso\Library;

class Updates extends \Phoundation\Core\Libraries\Updates
{
    /**
     * The current version for this library
     *
     * @return string
     */
    public function version(): string
    {
        return '0.2.0';
    }


    /**
     * The list of version updates available for this library
     *
     * @return void
     */
    public function updates(): void
    {
        $this->addUpdate('0.2.0', function () {
            // Drop the tables to be sure we have a clean slate
            sql()->getSchemaObject()->getTableObject('phoundation_sso_signins')->drop();

            // Create the phoundation_sso_signins table.
            sql()->getSchemaObject()->getTableObject('phoundation_sso_signins')->define()
                 ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `created_by` bigint DEFAULT NULL,
                    `meta_id` bigint NULL DEFAULT NULL,
                    `meta_state` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `status` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `users_id` bigint NOT NULL,
                    `last_sign_in` datetime DEFAULT NULL,
                    `sign_in_count` int NOT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    KEY `created_on` (`created_on`),
                    KEY `created_by` (`created_by`),
                    KEY `status` (`status`),
                    KEY `meta_id` (`meta_id`),
                    KEY `users_id` (`users_id`),
                    KEY `last_sign_in` (`last_sign_in`),
                    KEY `sign_in_count` (`sign_in_count`),
                ')->setForeignKeys('
                    CONSTRAINT `fk_phoundation_sso_signins_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_phoundation_sso_signins_meta_id` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
                    CONSTRAINT `fk_phoundation_sso_signins_users_id` FOREIGN KEY (`users_id`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                ')->create();
        });
    }
}
