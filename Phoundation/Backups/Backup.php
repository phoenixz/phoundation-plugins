<?php

/**
 * Class Backup
 *
 * This class manages a single backup. It can create a new backup, or restore an existing one
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Backup
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Backups;

use Phoundation\Core\Hooks\Hook;
use Phoundation\Core\Log\Log;
use Phoundation\Data\DataEntries\Interfaces\DataEntryInterface;
use Phoundation\Data\Traits\TraitDataGzip;
use Phoundation\Data\Traits\TraitDataTimeout;
use Phoundation\Databases\Connectors\Connector;
use Phoundation\Databases\Connectors\Interfaces\ConnectorInterface;
use Phoundation\Databases\Export;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Phoundation\Date\PhoDateTime;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Data\DataEntries\DataEntry;
use Phoundation\Data\DataEntries\Definitions\Definition;
use Phoundation\Data\DataEntries\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\Traits\TraitDataTarget;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Filesystem\Interfaces\PhoDirectoryInterface;
use Phoundation\Filesystem\Interfaces\PhoFileInterface;
use Phoundation\Data\Traits\TraitDataRestrictions;
use Phoundation\Web\Html\Enums\EnumInputType;


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
     * @var PhoDirectoryInterface|null $path
     */
    protected ?PhoDirectoryInterface $path = null;

    /**
     * The system date-time when this backup began
     *
     * @var PhoDateTimeInterface|null $date_time
     */
    protected ?PhoDateTimeInterface $date_time = null;


    /**
     * Initializes the backup object
     */
    public function __construct(int|array|string|DataEntryInterface|null $identifier = null)
    {
        parent::__construct($identifier);

        $this->date_time = PhoDateTime::new();
        $this->path      = PhoDirectory::new($this->target)
                                       ->addDirectory(PhoDateTime::new()->format('Ymd-his'))
                                       ->ensure();
    }


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
    public static function getEntryName(): string
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
     * Sets the target for the backups
     *
     * @param string|null $target
     * @return static
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
     * @return static
     */
    public function backupSystem(): static
    {
//        $this->init();

        return $this;
    }


    /**
     * Backs up plugin files
     *
     * @return static
     */
    public function backupPlugins(): static
    {
//        $this->init();

        return $this;
    }


    /**
     * Backs up data files
     *
     * @return static
     */
    public function backupDataFiles(): static
    {
//        $this->init();

        return $this;
    }


    /**
     * Dumps all the connectors for this project
     *
     * @return static
     */
    public function backupAllDatabases(): static
    {
//        $this->init();

        Log::action(ts('Backing up all configured connectors for environment ":environment"', [
            ':environment' => ENVIRONMENT,
        ]));

        $this->executeHook('pre-dump-all-databases');

        // Get connectors to back up
        $connectors = config()->getArray('databases.connectors');

        // Backup all databases in all connectors
        foreach ($connectors as $name => $connector) {
            $connector = Connector::new()->load($name);

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
     * @param ConnectorInterface $o_connector
     *
     * @return static
     */
    protected function backupConnectorDatabase(ConnectorInterface $o_connector): static
    {
        Log::action(ts('Backup up ":driver" database with connector ":connector"', [
            ':driver' => $o_connector->getDriver(),
            ':connector' => $o_connector->getDisplayName()
        ]));

        // ExecuteExecuteInterface the dump on the specified server
        $this->executeHook('pre-backup-database');

        Export::new()
            ->setConnectorObject($o_connector)
            ->setDatabase($o_connector->getDatabase())
            ->setDriver($o_connector->getDriver())
            ->setTimeout($this->timeout)
            ->setGzip($this->gzip)
            ->dump($this->getFile($o_connector));

        return $this->executeHook('post-backup-database');
    }


    /**
     * Returns the backup file to use
     *
     * @param ConnectorInterface|string $source
     *
     * @return PhoFileInterface
     */
    protected function getFile(ConnectorInterface|string $source): PhoFileInterface
    {
        if ($source instanceof ConnectorInterface){
            return $this->path . $this->date_time->format('Ymd-his') . '-' . strtolower($source->getDriver()) . '-' . $source->getDatabase() . '.sql';
        }

        return $this->path . $this->date_time->format('Ymd-his') . '-' . $source;
    }


    /**
     * Sets and returns the field definitions for the data fields in this DataEntry object
     *
     * @param DefinitionsInterface $definitions
     *
     * @return Backup
     */
    protected function setDefinitions(DefinitionsInterface $definitions): static
    {
        $definitions
            ->add(Definition::new('size')
                ->setReadonly(true)
                ->setInputType(EnumInputType::positiveInteger)
                ->setMin(0)
            );

        return $this;
    }


    /**
     * ExecuteExecuteInterface the specified hook(s)
     *
     * @param array|string $hooks
     * @return static
     */
    protected function executeHook(array|string $hooks): static
    {
        if (config()->get('backups.hooks.execute', true)) {
            Hook::new('backups')->execute($hooks);
        }

        return $this;
    }
}
