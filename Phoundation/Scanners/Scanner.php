<?php

/**
 * Class Scanner
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Scanner
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Scanners;

use Phoundation\Data\DataEntry\Interfaces\DataEntryInterface;
use Phoundation\Data\Traits\TraitDataBatch;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Filesystem\Interfaces\PhoPathInterface;
use Phoundation\Os\Processes\Commands\ScanImage;
use Phoundation\Utils\Arrays;
use Plugins\Phoundation\Hardware\Devices\Device;
use Plugins\Phoundation\Hardware\Devices\Interfaces\ProfileInterface;
use Plugins\Phoundation\Hardware\Exception\InvalidDeviceClassException;


class Scanner extends Device
{
    use TraitDataBatch;


    /**
     * The number of scanned documents
     *
     * @var int|null $scan_count
     */
    protected ?int $scan_count;


    /**
     * DataEntry class constructor
     *
     * @param array|DataEntryInterface|string|int|null $identifier
     * @param bool|null                                $meta_enabled
     * @param bool                                     $init
     */
    public function __construct(array|DataEntryInterface|string|int|null $identifier = null, ?bool $meta_enabled = null, bool $init = true)
    {
        parent::__construct($identifier, $meta_enabled, $init);

        if ($this->isNew()) {
            $this->setClass('scanner');

        } else {
            if ($this->getClass() !== 'scanner') {
                throw new InvalidDeviceClassException(tr('The specified device ":column=:identifier" is not a "scanner" class device', [
                    ':column'     => static::determineColumn($identifier),
                    ':identifier' => $identifier
                ]));
            }
        }
    }


    /**
     * Returns a DataEntry object matching the specified identifier that MUST exist in the database
     *
     * This method also accepts DataEntry objects of the same class, in which case it will simply return the specified
     * object, as long as it exists in the database.
     *
     * If the DataEntry does not exist in the database, then this method will check if perhaps it exists as a
     * configuration entry. This requires DataEntry::$config_path to be set. DataEntries from configuration will be in
     * readonly mode automatically as they cannot be stored in the database.
     *
     * DataEntries from the database will also have their status checked. If the status is "deleted", then a
     * DataEntryDeletedException will be thrown
     *
     * @note The test to see if a DataEntry object exists in the database can be either DataEntry::isNew() or
     *       DataEntry::getId(), which should return a valid database id
     *
     * @param array|DataEntryInterface|string|int|null $identifier
     * @param bool                                     $meta_enabled
     * @param bool                                     $ignore_deleted
     *
     * @return static
     */
    public static function load(array|DataEntryInterface|string|int|null $identifier, bool $meta_enabled = false, bool $ignore_deleted = false): static
    {
        $entry = parent::load($identifier, $meta_enabled, $ignore_deleted);

        if ($entry->getClass() !== 'scanner') {
            throw new InvalidDeviceClassException(tr('The specified device ":column=:identifier" is not a "scanner" class device', [
                ':column'     => static::determineColumn($identifier),
                ':identifier' => $identifier
            ]));
        }

        return $entry;
    }


    /**
     * Returns a DataEntry object matching the specified identifier that MUST exist in the database
     *
     * This method also accepts DataEntry objects of the same class, in which case it will simply return the specified
     * object, as long as it exists in the database.
     *
     * If the DataEntry does not exist in the database, then this method will check if perhaps it exists as a
     * configuration entry. This requires DataEntry::$config_path to be set. DataEntries from configuration will be in
     * readonly mode automatically as they cannot be stored in the database.
     *
     * DataEntries from the database will also have their status checked. If the status is "deleted", then a
     * DataEntryDeletedException will be thrown
     *
     * @note The test to see if a DataEntry object exists in the database can be either DataEntry::isNew() or
     *       DataEntry::getId(), which should return a valid database id
     *
     * @param array  $identifiers
     * @param bool   $meta_enabled
     * @param bool   $ignore_deleted
     * @param bool   $exception
     * @param string $filter
     * @return static|null
     */
    public static function find(array $identifiers, bool $meta_enabled = false, bool $ignore_deleted = false, bool $exception = true, string $filter = 'AND'): ?static
    {
        $entry = parent::find($identifiers, $meta_enabled, $ignore_deleted);

        if ($entry->getClass() !== 'scanner') {
            throw new InvalidDeviceClassException(tr('The specified device "identifiers" is not a "scanner" class device', [
                ':identifiers' => Arrays::implodeWithKeys($identifiers, ',', '=')
            ]));
        }

        return $entry;
    }


    /**
     * Returns the number of scanned documents, NULL if nothing has been scanned yet
     *
     * @return int|null
     */
    public function getScanCount(): ?int
    {
        return $this->scan_count;
    }


    /**
     * Scan using the specified profile
     *
     * @param ProfileInterface|string|int $profile
     * @param PhoPathInterface            $path
     *
     * @return static
     */
    public function scan(ProfileInterface|string|int $profile, PhoPathInterface $path): static
    {
        if (!$profile instanceof ProfileInterface) {
            $profile = $this->getProfiles()->get($profile);

            if ($profile->getDevice()->getId() !== $this->getId()) {
                throw new OutOfBoundsException(tr('Cannot use specified profile ":profile" for device ":device" because its for device ":wrong"', [
                    ':profile' => $profile->getLogId(),
                    ':device'  => $this->getLogId(),
                    ':wrong'   => $profile->getDevice()->getLogId(),
                ]));
            }
        }

        ScanImage::new()
                 ->applyProfile($profile)
                 ->setBatch($this->batch)
                 ->scan($path);

        return $this;
    }
}
