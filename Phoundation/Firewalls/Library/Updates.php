<?php

/**
 * Updates class
 *
 * This is the Init class for the Firewalls library
 *
 * @see \Phoundation\Core\Libraries\Updates
 * @author    Sven Olaf Oostenbrink <sven@medinet.ca>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Firewalls
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Firewalls\Library;


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
        return tr('This library contains Firewalls specific functionalities');
    }


    /**
     * The list of version updates available for this library
     *
     * @return void
     */
    public function updates(): void
    {
        $this->addUpdate('0.8.0', function () {
            // Drop the tables to be sure we have a clean slate
            sql()->getSchemaObject()->getTableObject('phoundation_firewalls')->drop()->getDefineObject()
                ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `created_by` bigint DEFAULT NULL,
                    `modified_on` timestamp NULL DEFAULT NULL,
                    `modified_by` bigint NULL DEFAULT NULL,
                    `meta_id` bigint NOT NULL,
                    `meta_state` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `status` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `action` varchar(32) NULL DEFAULT NULL,
                    `ip_address` varchar(46) NULL DEFAULT NULL,
                    `from` datetime NULL DEFAULT NULL,
                    `until` datetime NULL DEFAULT NULL,
                    `comments` text NULL DEFAULT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    KEY `created_on` (`created_on`),
                    KEY `created_by` (`created_by`),
                    KEY `modified_on` (`modified_on`),
                    KEY `modified_by` (`modified_by`),
                    KEY `status` (`status`),
                    KEY `action` (`action`),
                    KEY `ip_address` (`ip_address`),
                    KEY `from` (`from`),
                    KEY `until` (`until`),
                ')->setForeignKeys('
                    CONSTRAINT `fk_phoundation_firewall_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_phoundation_firewall_modified_by` FOREIGN KEY (`modified_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_phoundation_firewall_meta_id` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
                ')->create();
        });
    }
}
