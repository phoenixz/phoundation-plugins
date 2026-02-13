<?php

/**
 * Class Device
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Hardware
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices;

use Phoundation\Core\Log\Log;
use Phoundation\Data\DataEntries\DataEntry;
use Phoundation\Data\DataEntries\Definitions\Definition;
use Phoundation\Data\DataEntries\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntries\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryClass;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryComments;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryDescription;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryDevice;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryManufacturer;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryModel;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryName;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryProduct;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryType;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryUrl;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryVendor;
use Phoundation\Data\Validator\Interfaces\ValidatorInterface;
use Phoundation\Os\Processes\Commands\ScanImage;
use Phoundation\Servers\Traits\TraitDataEntryServer;
use Phoundation\Web\Html\Enums\EnumInputType;
use Plugins\Phoundation\Hardware\Devices\Interfaces\DeviceInterface;
use Plugins\Phoundation\Hardware\Devices\Interfaces\ProfilesInterface;
use Stringable;


class Device extends DataEntry implements DeviceInterface
{
    use TraitDataEntryClass;
    use TraitDataEntryComments;
    use TraitDataEntryDevice;
    use TraitDataEntryDescription;
    use TraitDataEntryManufacturer;
    use TraitDataEntryModel;
    use TraitDataEntryName;
    use TraitDataEntryProduct;
    use TraitDataEntryServer;
    use TraitDataEntryType;
    use TraitDataEntryUrl;
    use TraitDataEntryVendor;


    /**
     * Device options
     *
     * @var ProfilesInterface $profiles
     */
    protected ProfilesInterface $profiles;


    /**
     * @inheritDoc
     */
    public static function getTable(): ?string
    {
        return 'hardware_devices';
    }


    /**
     * @inheritDoc
     */
    public static function getEntryName(): string
    {
        return tr('Hardware device');
    }


    /**
     * @inheritDoc
     */
    public static function getUniqueColumn(): ?string
    {
        return 'seo_name';
    }


    /**
     * Returns the vendor_sString for this object
     *
     * @return string|null
     */
    public function getVendorString(): ?string
    {
        return $this->getTypesafe('string', 'vendor_string');
    }


    /**
     * Sets the vendor for this object
     *
     * @param Stringable|string|null $vendor
     * @return static
     */
    public function setVendorString(Stringable|string|null $vendor): static
    {
        return $this->set((string) $vendor, 'vendor_string');
    }


    /**
     * Returns the seo_vendor_sString for this object
     *
     * @return string|null
     */
    public function getSeoVendorString(): ?string
    {
        return $this->getTypesafe('string', 'seo_vendor_string');
    }


    /**
     * Sets the seo_vendor for this object
     *
     * @param Stringable|string|null $seo_vendor
     * @return static
     */
    protected function setSeoVendorString(Stringable|string|null $seo_vendor): static
    {
        return $this->set((string) $seo_vendor, 'seo_vendor_string');
    }


    /**
     * Returns the seo_product_sString for this object
     *
     * @return string|null
     */
    public function getSeoProductString(): ?string
    {
        return $this->getTypesafe('string', 'seo_product_string');
    }


    /**
     * Sets the seo_product for this object
     *
     * @param Stringable|string|null $seo_product
     * @return static
     */
    protected function setSeoProductString(Stringable|string|null $seo_product): static
    {
        return $this->set((string) $seo_product, 'seo_product_string');
    }


    /**
     * Returns the _product_sString for this object
     *
     * @return string|null
     */
    public function getProductString(): ?string
    {
        return $this->getTypesafe('string', 'product_string');
    }


    /**
     * Sets the _product for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setProductString(Stringable|string|null $_product): static
    {
        return $this->set((string) $_product, 'product_string');
    }


    /**
     * Returns the string for this object
     *
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->getTypesafe('string', 'string');
    }


    /**
     * Sets the string for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setString(Stringable|string|null $_product): static
    {
        return $this->set((string) $_product, 'string');
    }


    /**
     * Returns the seo string for this object
     *
     * @return string|null
     */
    public function getSeoString(): ?string
    {
        return $this->getTypesafe('string', 'seo_string');
    }


    /**
     * Sets the seo string for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    protected function setSeoString(Stringable|string|null $_product): static
    {
        return $this->set((string) $_product, 'seo_string');
    }


    /**
     * Returns the libusb for this object
     *
     * @return string|null
     */
    public function getLibusb(): ?string
    {
        return $this->getTypesafe('string', 'libusb');
    }


    /**
     * Sets the libusb for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setLibusb(Stringable|string|null $_product): static
    {
        return $this->set((string) $_product, 'libusb');
    }


    /**
     * Returns the bus for this object
     *
     * @return string|null
     */
    public function getBus(): ?string
    {
        return $this->getTypesafe('string', 'bus');
    }


    /**
     * Sets the bus for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setBus(Stringable|string|null $_product): static
    {
        return $this->set((string) $_product, 'bus');
    }


    /**
     * Returns the default for this object
     *
     * @return bool|null
     */
    public function getDefault(): ?bool
    {
        return $this->getTypesafe('bool', 'default');
    }


    /**
     * Sets the default for this object
     *
     * @param int|bool|null $_product
     * @return static
     */
    public function setDefault(int|bool|null $_product): static
    {
        return $this->set((bool) $_product, 'default');
    }


    /**
     * Searches for driver options for this device and stores them in the database
     *
     * @return static
     */
    public function updateOptions(): static
    {
        // Delete the default profile
        Profile::find([
            'devices_id' => $this->getId(),
            'name'       => 'options'
        ], exception: false)?->erase();

        // Create new default profile
        $profile = Profile::new()
            ->setDevicesId($this->getId())
            ->setName('options')
            ->save();

        Log::action(ts('Adding driver options for ":class" class device ":name"', [
            ':class' => $this->getClass(),
            ':name'  => $this->getName()
        ]));

        $options = $profile->getOptions();
        $found   = ScanImage::new()->listOptions($this->getDevice());

        foreach ($found as $option) {
            $options->add(Option::newFromSource($option)
                ->setDevicesId($this->getId())
                ->setProfilesId($profile->getId())
                ->save());
        }

        Log::success(ts('Added ":count" driver options for ":class" class device ":device"', [
            ':count'  => $options->getCount(),
            ':class'  => $this->getClass(),
            ':device' => $this->getDevice()
        ]));

        return $this;
    }


    /**
     * @return ProfilesInterface
     */
    public function getProfiles(): ProfilesInterface
    {
        if (empty($this->profiles)) {
            $this->profiles = Profiles::new()->setParentObject($this)->load();
        }

        return $this->profiles;
    }


    /**
     * @inheritDoc
     */
    protected function setDefinitionsObject(DefinitionsInterface $_definitions): static
    {
        $_definitions
            ->add(DefinitionFactory::newDatabaseId('servers_id'))

            ->add(DefinitionFactory::newServer())

            ->add(DefinitionFactory::newName()
                                   ->setOptional(false)
                                   ->setInputType(EnumInputType::name)
                                   ->setSize(12)
                                   ->setMaxLength(64)
                                   ->setHelpText(tr('The name for this device'))
                                   ->addValidationFunction(function (ValidatorInterface $_validator) {
                                       $_validator->isUnique();
                                   }))

            ->add(DefinitionFactory::newSeoName())

            ->add(Definition::new('class')
                            ->setOptional(false)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setSource([
                                'scanner'   => tr('Scanner'),
                                'printer'   => tr('Printer'),
                                'webcam'    => tr('Webcam'),
                                'biometric' => tr('Biometric'),
                            ])
                            ->setMaxLength(9)
                            ->setLabel(tr('Class')))

            ->add(Definition::new('type')
                            ->setOptional(false)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Type')))

            ->add(Definition::new('vendor')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setMaxLength(32)
                            ->setSize(3)
                            ->setLabel(tr('Vendor')))

            ->add(Definition::new('vendor_string')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Vendor string')))

            ->add(Definition::new('seo_vendor_string')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setRender(false)
                            ->setMaxLength(32))

            ->add(Definition::new('vendor')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Vendor')))

            ->add(Definition::new('manufacturer')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Manufacturer')))

            ->add(Definition::new('model')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Model')))

            ->add(Definition::new('product')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Product')))

            ->add(Definition::new('product_string')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Product string')))

            ->add(Definition::new('seo_product_string')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setRender(false)
                            ->setMaxLength(32))

            ->add(Definition::new('libusb')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Libusb')))

            ->add(Definition::new('bus')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(32)
                            ->setLabel(tr('Bus')))

            ->add(Definition::new('device')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(128)
                            ->setLabel(tr('Device')))

            ->add(Definition::new('string')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(128)
                            ->setLabel(tr('String')))

            ->add(Definition::new('seo_string')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setRender(false)
                            ->setSize(3)
                            ->setMaxLength(128))

            ->add(Definition::new('url')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::text)
                            ->setSize(3)
                            ->setMaxLength(2048)
                            ->setLabel(tr('URL')))

            ->add(Definition::new('default')
                            ->setOptional(true)
                            ->setInputType(EnumInputType::checkbox)
                            ->setSize(3)
                            ->setMaxLength(2048)
                            ->setLabel(tr('Default device')))

            ->add(DefinitionFactory::newDescription())

            ->add(DefinitionFactory::newComments());

        return $this;
    }
}
