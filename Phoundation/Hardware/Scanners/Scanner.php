<?php

/**
 * Class Scanner
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Hardware
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Scanners;

use Phoundation\Data\DataEntries\Interfaces\IdentifierInterface;
use Phoundation\Data\Enums\EnumLoadParameters;
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
     * @param IdentifierInterface|array|string|int|false|null $identifier
     */
    public function __construct(IdentifierInterface|array|string|int|false|null $identifier = false)
    {
        parent::__construct($identifier);

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
     * Loads the data for this Scanner object for the specified identifier
     *
     * @param IdentifierInterface|array|string|int|null $identifier                    Identifier for the DataEntry object to
     *                                                                                 load. Can be specified with a
     *                                                                                 [column => value] array, though also
     *                                                                                 accepts an integer value which will convert
     *                                                                                 to [id_column => integer_value] or a string
     *                                                                                 value which will convert to
     *                                                                                 [unique_column => string_value]]
     * @param EnumLoadParameters|null                   $on_load_null_identifier       Specifies how this load method will handle
     *                                                                                 the specified identifier being NULL.
     *                                                                                 Options are: EnumLoadParameters::exception
     *                                                                                 (Throws a
     *                                                                                 DataEntryNoIdentifierSpecifiedException),
     *                                                                                 EnumLoadParameters::null (will return NULL)
     *                                                                                 or EnumLoadParameters::this (Will return
     *                                                                                 the object as-is, without loading
     *                                                                                 anything). Defaults to
     *                                                                                 EnumLoadParameters::exception
     * @param EnumLoadParameters|null                   $on_load_not_exists            Specifies how this load method will handle
     *                                                                                 the specified identifier not existing in
     *                                                                                 the database. Options are:
     *                                                                                 EnumLoadParameters::exception (Throws a
     *                                                                                 DataEntryNotExistsException),
     *                                                                                 EnumLoadParameters::null (will return NULL)
     *                                                                                 or EnumLoadParameters::this (Will return
     *                                                                                 the object as-is, without loading anything)
     *                                                                                 Defaults to EnumLoadParameters::exception
     *
     * @return static|null
     */
    public function load(IdentifierInterface|array|string|int|null $identifier = null, ?EnumLoadParameters $on_load_null_identifier = null, ?EnumLoadParameters $on_load_not_exists = null): ?static
    {
        $entry = parent::load($identifier, $on_load_null_identifier, $on_load_not_exists);

        if ($entry->getClass() !== 'scanner') {
            throw new InvalidDeviceClassException(tr('The specified device ":column=:identifier" is not a "scanner" class device', [
                ':column'     => static::determineColumn($this->identifier),
                ':identifier' => $this->identifier
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
