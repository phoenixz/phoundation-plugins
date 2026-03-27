<?php

/**
 * Updates class
 *
 * This is the Init class for the Knowledgebase library
 *
 * @see \Phoundation\Core\Libraries\Updates
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase\Library;


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
     * The list of version updates available for this library
     *
     * @return void
     */
    public function updates(): void
    {
        $this->addUpdate('0.5.0', function () {
            // Create the health authorities table.
            sql()->getSchemaObject()->getTableObject('knowledgebase_articles')->drop()->getDefineObject()
                ->setColumns('
                    `id` bigint NOT NULL AUTO_INCREMENT,
                    `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `created_by` bigint DEFAULT NULL,
                    `meta_id` bigint NULL DEFAULT NULL,
                    `meta_state` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `status` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
                    `languages_id` bigint DEFAULT NULL,
                    `language` varchar(2) DEFAULT NULL,
                    `code` varchar(16) DEFAULT NULL,
                    `name` varchar(128) DEFAULT NULL,
                    `seo_name` varchar(128) DEFAULT NULL,
                    `body` mediumtext NULL DEFAULT NULL,
                ')->setIndices('                
                    PRIMARY KEY (`id`),
                    UNIQUE `seo_name` (`seo_name`),
                    KEY `created_on` (`created_on`),
                    KEY `created_by` (`created_by`),
                    KEY `status` (`status`),
                    KEY `code` (`code`),
                    KEY `name` (`name`),
                    KEY `language` (`language`),
                    KEY `languages_id` (`languages_id`),
                ')->setForeignKeys('
                    CONSTRAINT `fk_knowledgebase_articles_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts_users` (`id`) ON DELETE RESTRICT,
                    CONSTRAINT `fk_knowledgebase_articles_meta_id` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
                    CONSTRAINT `fk_knowledgebase_articles_languages_id` FOREIGN KEY (`languages_id`) REFERENCES `core_languages` (`id`) ON DELETE RESTRICT,
                ')->create();

        })->addUpdate('0.8.0', function () {
            // Add support for modified_on and modified_by
            $this->ensureModifiedColumns([
                'knowledgebase_articles',
            ]);
        });
    }
}
