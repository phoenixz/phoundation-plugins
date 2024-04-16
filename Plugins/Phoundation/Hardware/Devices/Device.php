<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices;

use Phoundation\Core\Log\Log;
use Phoundation\Data\DataEntry\DataEntry;
use Phoundation\Data\DataEntry\Definitions\Definition;
use Phoundation\Data\DataEntry\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryClass;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryComments;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryDescription;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryDevice;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryManufacturer;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryModel;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryName;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryProduct;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryType;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryUrl;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryVendor;
use Phoundation\Data\Validator\Interfaces\ValidatorInterface;
use Phoundation\Os\Processes\Commands\ScanImage;
use Phoundation\Servers\Traits\TraitDataEntryServer;
use Phoundation\Web\Html\Enums\EnumInputType;
use Plugins\Phoundation\Hardware\Devices\Interfaces\DeviceInterface;
use Plugins\Phoundation\Hardware\Devices\Interfaces\ProfilesInterface;
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
    public static function getTable(): string
    {
        return 'hardware_devices';
    }


    /**
     * @inheritDoc
     */
    public static function getDataEntryName(): string
    {
        return tr('Hardware device');
    }


    /**
     * @inheritDoc
     */
    public static function getUniqueColumn(): ?string
    {
        return 'name';
    }


    /**
     * Returns the vendor_sString for this object
     *
     * @return string|null
     */
    public function getVendorString(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'vendor_string');
    }


    /**
     * Sets the vendor for this object
     *
     * @param Stringable|string|null $vendor
     * @return static
     */
    public function setVendorString(Stringable|string|null $vendor): static
    {
        return $this->setSourceValue('vendor_string', (string) $vendor);
    }


    /**
     * Returns the seo_vendor_sString for this object
     *
     * @return string|null
     */
    public function getSeoVendorString(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'seo_vendor_string');
    }


    /**
     * Sets the seo_vendor for this object
     *
     * @param Stringable|string|null $seo_vendor
     * @return static
     */
    protected function setSeoVendorString(Stringable|string|null $seo_vendor): static
    {
        return $this->setSourceValue('seo_vendor_string', (string) $seo_vendor);
    }


    /**
     * Returns the seo_product_sString for this object
     *
     * @return string|null
     */
    public function getSeoProductString(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'seo_product_string');
    }


    /**
     * Sets the seo_product for this object
     *
     * @param Stringable|string|null $seo_product
     * @return static
     */
    protected function setSeoProductString(Stringable|string|null $seo_product): static
    {
        return $this->setSourceValue('seo_product_string', (string) $seo_product);
    }


    /**
     * Returns the _product_sString for this object
     *
     * @return string|null
     */
    public function getProductString(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'product_string');
    }


    /**
     * Sets the _product for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setProductString(Stringable|string|null $_product): static
    {
        return $this->setSourceValue('product_string', (string) $_product);
    }


    /**
     * Returns the string for this object
     *
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'string');
    }


    /**
     * Sets the string for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setString(Stringable|string|null $_product): static
    {
        return $this->setSourceValue('string', (string) $_product);
    }


    /**
     * Returns the seo string for this object
     *
     * @return string|null
     */
    public function getSeoString(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'seo_string');
    }


    /**
     * Sets the seo string for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    protected function setSeoString(Stringable|string|null $_product): static
    {
        return $this->setSourceValue('seo_string', (string) $_product);
    }


    /**
     * Returns the libusb for this object
     *
     * @return string|null
     */
    public function getLibusb(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'libusb');
    }


    /**
     * Sets the libusb for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setLibusb(Stringable|string|null $_product): static
    {
        return $this->setSourceValue('libusb', (string) $_product);
    }


    /**
     * Returns the bus for this object
     *
     * @return string|null
     */
    public function getBus(): ?string
    {
        return $this->getSourceValueTypesafe('string', 'bus');
    }


    /**
     * Sets the bus for this object
     *
     * @param Stringable|string|null $_product
     * @return static
     */
    public function setBus(Stringable|string|null $_product): static
    {
        return $this->setSourceValue('bus', (string) $_product);
    }


    /**
     * Returns the default for this object
     *
     * @return bool|null
     */
    public function getDefault(): ?bool
    {
        return $this->getSourceValueTypesafe('bool', 'default');
    }


    /**
     * Sets the default for this object
     *
     * @param int|bool|null $_product
     * @return static
     */
    public function setDefault(int|bool|null $_product): static
    {
        return $this->setSourceValue('default', (bool) $_product);
    }


    /**
     * Searches for driver options for this device and stores them in the database
     *
     * @return $this
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

        Log::action(tr('Adding driver options for ":class" class device ":name"', [
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

        Log::success(tr('Added ":count" driver options for ":class" class device ":device"', [
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
            $this->profiles = Profiles::new()->setParent($this)->load();
        }

        return $this->profiles;
    }


    /**
     * @inheritDoc
     */
    protected function setDefinitions(DefinitionsInterface $definitions): void
    {
        $definitions
            ->add(DefinitionFactory::getServersId($this))
            ->add(DefinitionFactory::getServer($this))
            ->add(DefinitionFactory::getName($this)
                ->setOptional(false)
                ->setInputType(EnumInputType::name)
                ->setSize(12)
                ->setMaxlength(64)
                ->setHelpText(tr('The name for this role'))
                ->addValidationFunction(function (ValidatorInterface $validator) {
                    $validator->isUnique(tr('value ":name" already exists', [':name' => $validator->getSelectedValue()]));
                }))
            ->add(DefinitionFactory::getSeoName($this))
            ->add(Definition::new($this, 'class')
                ->setOptional(false)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setDataSource([
                    'scanner'   => tr('Scanner'),
                    'printer'   => tr('Printer'),
                    'webcam'    => tr('Webcam'),
                    'biometric' => tr('Biometric'),
                ])
                ->setMaxlength(9)
                ->setLabel(tr('Class')))
            ->add(Definition::new($this, 'type')
                ->setOptional(false)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Type')))
            ->add(Definition::new($this, 'vendor')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setMaxlength(32)
                ->setSize(3)
                ->setLabel(tr('Vendor')))
            ->add(Definition::new($this, 'vendor_string')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Vendor string')))
            ->add(Definition::new($this, 'seo_vendor_string')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setRender(false)
                ->setMaxlength(32))
            ->add(Definition::new($this, 'vendor')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Vendor')))
            ->add(Definition::new($this, 'manufacturer')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Manufacturer')))
            ->add(Definition::new($this, 'model')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Model')))
            ->add(Definition::new($this, 'product')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Product')))
            ->add(Definition::new($this, 'product_string')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Product string')))
            ->add(Definition::new($this, 'seo_product_string')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setRender(false)
                ->setMaxlength(32))
            ->add(Definition::new($this, 'libusb')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Libusb')))
            ->add(Definition::new($this, 'bus')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(32)
                ->setLabel(tr('Bus')))
            ->add(Definition::new($this, 'device')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(128)
                ->setLabel(tr('Device')))
            ->add(Definition::new($this, 'string')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(128)
                ->setLabel(tr('String')))
            ->add(Definition::new($this, 'seo_string')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setRender(false)
                ->setSize(3)
                ->setMaxlength(128))
            ->add(Definition::new($this, 'url')
                ->setOptional(true)
                ->setInputType(EnumInputType::text)
                ->setSize(3)
                ->setMaxlength(2048)
                ->setLabel(tr('URL')))
            ->add(Definition::new($this, 'default')
                ->setOptional(true)
                ->setInputType(EnumInputType::checkbox)
                ->setSize(3)
                ->setMaxlength(2048)
                ->setLabel(tr('Default device')))
            ->add(DefinitionFactory::getDescription($this))
            ->add(DefinitionFactory::getComments($this));
    }
}
