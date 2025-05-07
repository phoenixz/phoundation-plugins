<?php

/**
 * Class Profile
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Hardware
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices;

use Phoundation\Data\DataEntries\DataEntry;
use Phoundation\Data\DataEntries\Definitions\Definition;
use Phoundation\Data\DataEntries\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntries\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntries\Exception\DataEntryAlreadyExistsException;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryComments;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryDescription;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryDeviceObject;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryName;
use Phoundation\Data\Validator\Interfaces\ValidatorInterface;
use Phoundation\Utils\Arrays;
use Phoundation\Utils\Utils;
use Phoundation\Web\Html\Enums\EnumInputType;
use Plugins\Phoundation\Hardware\Devices\Interfaces\OptionsInterface;
use Plugins\Phoundation\Hardware\Devices\Interfaces\ProfileInterface;


class Profile extends DataEntry implements ProfileInterface
{
    use TraitDataEntryComments;
    use TraitDataEntryDescription;
    use TraitDataEntryDeviceObject;
    use TraitDataEntryName;


    /**
     * The device driver options for this profile
     *
     * @var OptionsInterface
     */
    protected OptionsInterface $options;


    /**
     * @inheritDoc
     */
    public static function getTable(): ?string
    {
        return 'hardware_profiles';
    }


    /**
     * @inheritDoc
     */
    public static function getEntryName(): string
    {
        return tr('Hardware profile');
    }


    /**
     * @inheritDoc
     */
    public static function getUniqueColumn(): ?string
    {
        return 'name';
    }


    /**
     * Returns  if this profile is the default profile or not
     *
     * @return bool|null
     */
    public function getDefault(): ?bool
    {
        return $this->getTypesafe('string', 'default');
    }


    /**
     * Sets if this profile is the default profile or not
     *
     * @param int|bool|null $default
     * @return static
     */
    public function setDefault(int|bool|null $default): static
    {
        return $this->set($default, 'default');
    }


    /**
     * Returns the device driver options list for this profile
     *
     * @return OptionsInterface
     */
    public function getOptions(): OptionsInterface
    {
        if (empty($this->options)) {
            $this->options = Options::new()->setParentObject($this)->load();
        }

        return $this->options;
    }


    /**
     * Erase this profile from the database and with it all linked hardware device options
     *
     * @return static
     */
    public function erase(): static
    {
        // Erase all linked driver options
        sql()->query('DELETE FROM `hardware_options` WHERE `profiles_id` = :profiles_id', [
            ':profiles_id' => $this->getId()
        ]);

        return parent::erase();
    }


    /**
     * Copy this profile to the specified target name, taking the specified option keys with it
     *
     * @param string $target
     * @param array|string $keys
     * @param bool $force
     * @return static
     */
    public function copy(string $target, array|string $keys, bool $force = false): static
    {
        // Delete the default profile
        $profile = Profile::find([
            'devices_id' => $this->getDevice()->getId(),
            'name'       => $target
        ], exception: false);

        if ($profile) {
            // $this profile already exists!
            if (!$force) {
                throw new DataEntryAlreadyExistsException(tr('The specified target profile ":target" already exists', [
                    ':target' => $target
                ]));
            }

            $profile->erase();
        }

        // Ensure keys is an array and create new target profile
        $keys    = Arrays::force($keys);
        $profile = Profile::new()
            ->setDevicesId($this->getDevicesId())
            ->setName($target)
            ->save();

        // Copy the options to the new profile
        $keys = Arrays::keepMatchingValues($this->getOptions()->getSourceKeys(), $keys, Utils::MATCH_CASE_INSENSITIVE | Utils::MATCH_ANY | Utils::MATCH_ENDS_WITH);

        foreach ($this->getOptions() as $key => $option) {
            if (in_array($key, $keys)) {
                $option = clone($option);
                $option->setProfilesId($profile->getId())->save();

                $profile->getOptions()->add($option);
            }
        }

        // Return the new profile
        return $profile;
    }


    /**
     * @inheritDoc
     */
    protected function setDefinitionsObject(DefinitionsInterface $o_definitions): static
    {
        $o_definitions
            ->add(Definition::new('devices_id')
                ->setRender(true)
                ->setOptional(true)
                ->setSize(4)
                ->addValidationFunction(function (ValidatorInterface $validator) {
                    // Validate the programs id
                    $validator->orColumn('device')->isDbId()->isQueryResult('SELECT `id` FROM `hardware_devices` WHERE `id` = :id AND `status` IS NULL', [':id' => '$devices_id']);
                }))

            ->add(Definition::new('device')
                ->setOptional(true)
                ->setVirtual(true)
                ->setRender(false)
                ->setSize(4)
                ->setInputType(EnumInputType::select)
                ->addValidationFunction(function (ValidatorInterface $validator) {
                    // Validate the device name
                    $validator->orColumn('devices_id')->isVariable()->setColumnFromQuery('programs_id', 'SELECT `id` FROM `hardware_devices` WHERE `name` = :name AND `status` IS NULL', [':name' => '$device']);
                })
                ->setLabel(tr('Device'))
                ->setHelpText(tr('The device this driver option belongs')))

            ->add(DefinitionFactory::newName())

            ->add(DefinitionFactory::newSeoName())

            ->add(Definition::new('default')
                ->setRender(true)
                ->setOptional(true, false)
                ->setInputType(EnumInputType::checkbox)
                ->setLabel(tr('Default profile'))
            )

            ->add(DefinitionFactory::newComments());

        return $this;
    }
}
