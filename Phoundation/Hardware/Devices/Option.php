<?php

/**
 * Class Option
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
use Phoundation\Data\DataEntries\Traits\TraitDataEntryComments;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryDescription;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryDeviceObject;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryProfileObject;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryUnits;
use Phoundation\Data\Validator\Exception\ValidationFailedException;
use Phoundation\Data\Validator\Interfaces\ValidatorInterface;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Utils\Arrays;
use Phoundation\Web\Html\Enums\EnumInputType;
use Plugins\Phoundation\Hardware\Devices\Interfaces\OptionInterface;
use ReturnTypeWillChange;
use Stringable;

class Option extends DataEntry implements OptionInterface
{
    use TraitDataEntryComments;
    use TraitDataEntryDescription;
    use TraitDataEntryUnits;
    use TraitDataEntryDeviceObject;
    use TraitDataEntryProfileObject;


    /**
     * @inheritDoc
     */
    public static function getTable(): ?string
    {
        return 'hardware_options';
    }


    /**
     * @inheritDoc
     */
    public static function getEntryName(): string
    {
        return tr('Device driver option');
    }


    /**
     * @inheritDoc
     */
    public static function getUniqueColumn(): ?string
    {
        return 'key';
    }


    /**
     * Returns the key for this option
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->getTypesafe('string', 'key');
    }


    /**
     * Sets the key for this option
     *
     * @param string|null $key
     * @return static
     */
    public function setKey(?string $key): static
    {
        return $this->set($key, 'key');
    }


    /**
     * Returns the value for this option
     *
     * @param float|Stringable|int|string $key
     * @param bool                        $exception
     *
     * @return string|null
     */
    #[ReturnTypeWillChange] public function get(float|Stringable|int|string $key = 'value', bool $exception = true): mixed
    {
        return parent::get($key, $exception);
    }


    /**
     * Sets the value for this option
     *
     * The value must either be one of the values option, or fall within the range for this option
     *
     * @param mixed                       $value
     * @param float|Stringable|int|string $key
     *
     * @return static
     */
    #[ReturnTypeWillChange] public function set(mixed $value, float|Stringable|int|string $key = 'value'): static
    {
        if ($value) {
            $this->checkRange($value)
                 ->checkValues($value);
        }

        return parent::set($value, $key);
    }


    /**
     * Checks if the value is valid for this option
     *
     * @param mixed $value
     * @return static
     */
    protected function checkValues(mixed $value): static
    {
        if (!is_string($value)) {
            if ($value !== null) {
                throw new OutOfBoundsException(tr('Invalid value datatype ":value" specified, must be NULL or string', [
                    ':value' => $value
                ]));
            }
        }

        $values = $this->getValues();

        if ($values) {
            $values = Arrays::force($values, ',');

            if (!in_array($value, $values)) {
                throw new ValidationFailedException(tr('Specified value ":value" for option ":option" is not one of required ":values"', [
                    ':value'  => $value,
                    ':values' => $values,
                    ':option' => $this->getKey()
                ]));
            }
        }

        return $this;
    }


    /**
     * Checks if the value is valid for this option
     *
     * @param string|null $value
     * @return static
     */
    protected function checkRange(?string $value): static
    {
        $range = $this->getRange();

        if ($range) {
            show($value);
            showdie($range);
            $range = Arrays::force($range, ',');

            if (!in_range($value, $range[0], $range[1])) {
                throw new ValidationFailedException(tr('Specified value ":value" for option ":option" is not within the required range of ":values"', [
                    ':value'  => $value,
                    ':values' => implode('...', $range),
                    ':option' => $this->getKey()
                ]));
            }
        }

        return $this;
    }


    /**
     * Returns the values for this option
     *
     * @return string|null
     */
    public function getValues(): ?string
    {
        return $this->getTypesafe('string', 'values');
    }


    /**
     * Sets the values for this option
     *
     * @param string|null $values
     * @return static
     */
    public function setValues(?string $values): static
    {
        return $this->set($values, 'values');
    }


    /**
     * Returns the range for this option
     *
     * @return string|null
     */
    public function getRange(): ?string
    {
        return $this->getTypesafe('string', 'range');
    }


    /**
     * Sets the range for this option
     *
     * @param string|null $range
     * @return static
     */
    public function setRange(?string $range): static
    {
        return $this->set($range, 'range');
    }


    /**
     * Returns the default for this option
     *
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->getTypesafe('string', 'default');
    }


    /**
     * Sets the default for this option
     *
     * @param string|null $default
     * @return static
     */
    public function setDefault(?string $default): static
    {
        return $this->set($default, 'default');
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
                                // Validate the devices id
                                $validator->orColumn('device')->isDbId()->isQueryResult('SELECT `id` FROM `hardware_devices` WHERE `id` = :id AND `status` IS NULL', [
                                    ':id' => '$devices_id'
                                ]);
                            }))

            ->add(Definition::new('device')
                            ->setOptional(true)
                            ->setVirtual(true)
                            ->setRender(false)
                            ->setSize(4)
                            ->setInputType(EnumInputType::select)
                            ->addValidationFunction(function (ValidatorInterface $validator) {
                                // Validate the device name
                                $validator->orColumn('devices_id')->isVariable()->setColumnFromQuery('programs_id', 'SELECT `id` FROM `hardware_devices` WHERE `name` = :name AND `status` IS NULL', [
                                    ':name' => '$device'
                                ]);
                            })
                            ->setLabel(tr('Device'))
                            ->setHelpText(tr('The device this driver option belongs')))

            ->add(Definition::new('profiles_id')
                ->setRender(true)
                ->setOptional(true)
                ->setSize(4)
                ->addValidationFunction(function (ValidatorInterface $validator) {
                    // Validate the programs id
                    $validator
                        ->xorColumn('profile')
                        ->isDbId()
                        ->isQueryResult('SELECT `id` FROM `hardware_profiles` WHERE `id` = :id AND `status` IS NULL', [
                            ':id' => '$profiles_id'
                        ]);
                }))

            ->add(Definition::new('profile')
                ->setOptional(true)
                ->setVirtual(true)
                ->setRender(false)
                ->setSize(4)
                ->setInputType(EnumInputType::select)
                ->addValidationFunction(function (ValidatorInterface $validator) {
                    // Validate the profile name
                    $validator
                        ->xorColumn('profiles_id')
                        ->isName()
                        ->setColumnFromQuery('programs_id', 'SELECT `id` FROM `hardware_profiles` WHERE `name` = :name AND `status` IS NULL', [
                            ':name' => '$profile'
                        ]);
                })
                ->setLabel(tr('Profile'))
                ->setHelpText(tr('The profile this driver option belongs to')))

            ->add(Definition::new('key')
                ->setOptional(false)
                ->setRender(true)
                ->setSize(4)
                ->setMaxlength(32))

            ->add(Definition::new('value')
                ->setOptional(false)
                ->setRender(true)
                ->setSize(4)
                ->setMaxlength(255))

            ->add(Definition::new('default')
                ->setOptional(false)
                ->setRender(true)
                ->setSize(4)
                ->setMaxlength(255))

            ->add(Definition::new('range')
                ->setOptional(false)
                ->setRender(true)
                ->setSize(4)
                ->setMaxlength(64))

            ->add(Definition::new('values')
                ->setOptional(false)
                ->setRender(true)
                ->setSize(4)
                ->setMaxlength(255))

            ->add(Definition::new('units')
                ->setOptional(true)
                ->setRender(true)
                ->setSize(4)
                ->setMaxlength(16))

            ->add(DefinitionFactory::newComments()
                ->setMaxlength(255))

            ->add(DefinitionFactory::newDescription()
                ->setMaxlength(2048));

        return $this;
    }
}
