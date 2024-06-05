<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Backups;

use Phoundation\Core\Hooks\Hook;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Traits\TraitDataGzip;
use Phoundation\Data\Traits\TraitDataTimeout;
use Phoundation\Databases\Connectors\Connector;
use Phoundation\Databases\Connectors\Interfaces\ConnectorInterface;
use Phoundation\Databases\Export;
use Phoundation\Date\DateTime;
use Phoundation\Filesystem\Directory;
use Phoundation\Data\DataEntry\DataEntry;
use Phoundation\Data\DataEntry\Definitions\Definition;
use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\Traits\TraitDataTarget;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Filesystem\Traits\TraitDataRestrictions;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Enums\EnumElementInputType;

/**
 * Class Backup
 *
 * This class manages a single backup. It can create a new backup, or restore an existing one
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Accounts
 */

class Backup extends DataEntry
{
    use TraitDataRestrictions;
    use TraitDataTarget {
        setTarget as protected __setTarget;
    }
    use TraitDataGzip;
    use TraitDataTimeout;


    /**
     * @var bool $init
     */
    protected bool $init = false;

    /**
     * @var string|null $path
     */
    protected ?string $path = null;

    /**
     * The system date-time when this backup began
     *
     * @var DateTime|null $date_time
     */
    protected ?DateTime $date_time = null;


    /**
     * Returns the table name used by this object
     *
     * @return string|null
     */
    public static function getTable(): ?string
    {
        return 'backups';
    }


    /**
     * Returns the name of this DataEntry class
     *
     * @return string
     */
    public static function getDataEntryName(): string
    {
        return tr('Syustem backup');
    }


    /**
     * Returns the field that is unique for this object
     *
     * @return string|null
     */
    public static function getUniqueColumn(): ?string
    {
        return null;
    }


    /**
     * Initializes the backup object
     *
     * @return $this
     */
    protected function init(): static
    {
        $this->path = Directory::new($this->target)
            ->addDirectory(DateTime::new()->format('Ymd-his'))
            ->ensure()
            ->getPath();

        $this->date_time = DateTime::new();

        return $this;
    }


    /**
     * Sets the target for the backups
     *
     * @param string|null $target
     * @return $this
     */
    public function setTarget(?string $target): static
    {
        if ($this->init) {
            throw new OutOfBoundsException(tr('Cannot set new target, the backup class has already been initialized for target ":target"', [
                ':target' => $this->target
            ]));
        }

        return $this->__setTarget($target);
    }


    /**
     * Returns true if the backup class has been initialized
     *
     * @return bool
     */
    public function getInit(): bool
    {
        return $this->init;
    }


    /**
     * Backs up system files
     *
     * @return $this
     */
    public function backupSystem(): static
    {
        $this->init();

        return $this;
    }


    /**
     * Backs up plugin files
     *
     * @return $this
     */
    public function backupPlugins(): static
    {
        $this->init();

        return $this;
    }


    /**
     * Backs up data files
     *
     * @return $this
     */
    public function backupDataFiles(): static
    {
        $this->init();

        return $this;
    }


    /**
     * Dumps all the connectors for this project
     *
     * @return static
     */
    public function backupAllDatabases(): static
    {
        $this->init();

        Log::action(tr('Backing up all configured connectors for environment ":environment"', [
            ':environment' => ENVIRONMENT,
        ]));

        $this->executeHook('pre-dump-all-databases');

        // Get connectors to back up
        $connectors = Config::getArray('databases.connectors');

        // Backup all databases in all connectors
        foreach ($connectors as $name => $connector) {
            $connector = Connector::load($name);

            if ($connector->getBackup()) {
                if ($connector->getType() === 'memcached') {
                    // Memcached is volatile, contains only temp data, and cannot (and should not) be dumped
                    continue;
                }

                $this->backupConnectorDatabase($connector);
            }
        }

        return $this->executeHook('post-backup-all-databases');
    }


    /**
     * Backs up the specified connector
     *
     * @param ConnectorInterface $connector
     * @return static
     */
    protected function backupConnectorDatabase(ConnectorInterface $connector): static
    {
        Log::action(tr('Backup up ":driver" database with connector ":connector"', [
            ':driver'      => $connector->getDriver(),
            ':connector'   => $connector->getDisplayName()
        ]));

        // Execute the dump on the specified server
        $this->executeHook('pre-backup-database');

        Export::new()
            ->setConnector($connector)
            ->setDatabase($connector->getDatabase())
            ->setDriver($connector->getDriver())
            ->setTimeout($this->timeout)
            ->setGzip($this->gzip)
            ->dump($this->getFile($connector));

        return $this->executeHook('post-backup-database');
    }


    /**
     * Returns the backup file to use
     *
     * @param ConnectorInterface|string $source
     * @return string
     */
    protected function getFile(ConnectorInterface|string $source): string
    {
        if ($source instanceof ConnectorInterface){
            return $this->path . $this->date_time->format('Ymd-his') . '-' . strtolower($source->getDriver()) . '-' . $source->getDatabase() . '.sql';
        }

        return $this->path . $this->date_time->format('Ymd-his') . '-' . $source;
    }


    /**
     * Sets and returns the field definitions for the data fields in this DataEntry object
     *
     * Format:
     *
     * [
     *   field => [key => value],
     *   field => [key => value],
     *   field => [key => value],
     * ]
     *
     * "field" should be the database table column name
     *
     * Field keys:
     *
     * FIELD          DATATYPE           DEFAULT VALUE  DESCRIPTION
     * value          mixed              null           The value for this entry
     * visible        boolean            true           If false, this key will not be shown on web, and be readonly
     * virtual        boolean            false          If true, this key will be visible and can be modified but it
     *                                                  won't exist in database. It instead will be used to generate
     *                                                  a different field
     * element        string|null        "input"        Type of element, input, select, or text or callable function
     * type           string|null        "text"         Type of input element, if element is "input"
     * readonly       boolean            false          If true, will make the input element readonly
     * disabled       boolean            false          If true, the field will be displayed as disabled
     * label          string|null        null           If specified, will show a description label in HTML
     * size           int [1-12]         12             The HTML boilerplate column size, 1 - 12 (12 being the whole
     *                                                  row)
     * source         array|string|null  null           Array or query source to get contents for select, or single
     *                                                  value for text inputs
     * execute        array|null         null           Bound execution variables if specified "source" is a query
     *                                                  string
     * complete       array|bool|null    null           If defined must be bool or contain array with key "noword"
     *                                                  and "word". each key must contain a callable function that
     *                                                  returns an array with possible words for shell auto
     *                                                  completion. If bool, the system will generate this array
     *                                                  automatically from the rows for this field
     * cli            string|null        null           If set, defines the alternative column name definitions for
     *                                                  use with CLI. For example, the column may be name, whilst
     *                                                  the cli column name may be "-n,--name"
     * optional       boolean            false          If true, the field is optional and may be left empty
     * title          string|null        null           The title attribute which may be used for tooltips
     * placeholder    string|null        null           The placeholder attribute which typically shows an example
     * maxlength      string|null        null           The maxlength attribute which typically shows an example
     * pattern        string|null        null           The pattern the value content should match in browser client
     * min            string|null        null           The minimum amount for numeric inputs
     * max            string|null        null           The maximum amount for numeric inputs
     * step           string|null        null           The up / down step for numeric inputs
     * default        mixed              null           If "value" for entry is null, then default will be used
     * null_disabled  boolean            false          If "value" for entry is null, then use this for "disabled"
     * null_readonly  boolean            false          If "value" for entry is null, then use this for "readonly"
     * null_type      boolean            false          If "value" for entry is null, then use this for "type"
     *
     * @param DefinitionsInterface $definitions
     */
    protected function setDefinitions(DefinitionsInterface $definitions): void
    {
        $definitions
            ->add(Definition::new($this, 'size')
                ->setReadonly(true)
                ->setInputType(EnumElementInputType::positiveInteger)
                ->setMin(0)
            );
    }


    /**
     * Execute the specified hook(s)
     *
     * @param array|string $hooks
     * @return static
     */
    protected function executeHook(array|string $hooks): static
    {
        if (Config::get('backups.hooks.execute', true)) {
            Hook::new('backups')->execute($hooks);
        }

        return $this;
    }
}
