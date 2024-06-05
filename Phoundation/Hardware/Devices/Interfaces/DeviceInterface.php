<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

use Phoundation\Data\DataEntry\Interfaces\DataEntryInterface;
use Stringable;


/**
 * Class Device
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Hardware
 */
interface DeviceInterface extends DataEntryInterface
{
    /**
     * Returns the vendor_sString for this object
     *
     * @return string|null
     */
    public function getVendorString(): ?string;

    /**
     * Sets the vendor for this object
     *
     * @param Stringable|string|null $vendor
     * @return static
     */
    public function setVendorString(Stringable|string|null $vendor): static;

    /**
     * Returns the seo_vendor_sString for this object
     *
     * @return string|null
     */
    public function getSeoVendorString(): ?string;

    /**
     * Returns the seo_product_sString for this object
     *
     * @return string|null
     */
    public function getSeoProductString(): ?string;

    /**
     * Returns the _product_sString for this object
     *
     * @return string|null
     */
    public function getProductString(): ?string;

    /**
     * Sets the _product for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setProductString(Stringable|string|null $_product): static;

    /**
     * Returns the string for this object
     *
     * @return string|null
     */
    public function getString(): ?string;

    /**
     * Sets the string for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setString(Stringable|string|null $_product): static;

    /**
     * Returns the seo string for this object
     *
     * @return string|null
     */
    public function getSeoString(): ?string;

    /**
     * Returns the libusb for this object
     *
     * @return string|null
     */
    public function getLibusb(): ?string;

    /**
     * Sets the libusb for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setLibusb(Stringable|string|null $_product): static;

    /**
     * Returns the bus for this object
     *
     * @return string|null
     */
    public function getBus(): ?string;

    /**
     * Sets the bus for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setBus(Stringable|string|null $_product): static;

    /**
     * Returns the default for this object
     *
     * @return bool|null
     */
    public function getDefault(): ?bool;

    /**
     * Sets the default for this object
     *
     * @param int|bool|null $_product
     * @return static
     */
    public function setDefault(int|bool|null $_product): static;

    /**
     * Searches for driver options for this device and stores them in the database
     *
     * @return $this
     */
    public function updateOptions(): static;

    /**
     * @return ProfilesInterface
     */
    public function getProfiles(): ProfilesInterface;
}
