<?php

/**
 * Class Backup
 *
 * This class manages a single backup. It can create a new backup, or restore an existing one
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Accounts
 */


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
use Phoundation\Filesystem\FsDirectory;
use Phoundation\Data\DataEntry\DataEntry;
use Phoundation\Data\DataEntry\Definitions\Definition;
use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\Traits\TraitDataTarget;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Filesystem\Interfaces\FsDirectoryInterface;
use Phoundation\Filesystem\Interfaces\FsFileInterface;
use Phoundation\Data\Traits\TraitDataRestrictions;
use Phoundation\Utils\Config;
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
     * @var FsDirectoryInterface|null $path
     */
    protected ?FsDirectoryInterface $path = null;

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
     * @return static
     */
    protected function init(): static
    {
        $this->date_time = DateTime::new();
        $this->path      = FsDirectory::new($this->target)
                                      ->addDirectory(DateTime::new()->format('Ymd-his'))
                                      ->ensure();

        return $this;
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
     * @param ConnectorInterface $o_connector
     *
     * @return static
     */
    protected function backupConnectorDatabase(ConnectorInterface $o_connector): static
    {
        Log::action(tr('Backup up ":driver" database with connector ":connector"', [
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
     * @return FsFileInterface
     */
    protected function getFile(ConnectorInterface|string $source): FsFileInterface
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
     */
    protected function setDefinitions(DefinitionsInterface $definitions): void
    {
        $definitions
            ->add(Definition::new($this, 'size')
                ->setReadonly(true)
                ->setInputType(EnumInputType::positiveInteger)
                ->setMin(0)
            );
    }


    /**
     * ExecuteExecuteInterface the specified hook(s)
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
