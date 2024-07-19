<?php

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

interface OptionInterface
{
    /**
     * Returns the key for this option
     *
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * Sets the key for this option
     *
     * @param string|null $key
     * @return static
     */
    public function setKey(?string $key): static;

    /**
     * Returns the value for this option
     *
     * @return string|null
     */
    public function get(): ?string;

    /**
     * Sets the value for this option
     *
     * The value must either be one of the values option, or fall within the range for this option
     *
     * @param string|null $value
     * @return static
     */
    public function set(?string $value): static;

    /**
     * Returns the values for this option
     *
     * @return string|null
     */
    public function getValues(): ?string;

    /**
     * Sets the values for this option
     *
     * @param string|null $values
     * @return static
     */
    public function setValues(?string $values): static;

    /**
     * Returns the range for this option
     *
     * @return string|null
     */
    public function getRange(): ?string;

    /**
     * Sets the range for this option
     *
     * @param string|null $range
     * @return static
     */
    public function setRange(?string $range): static;

    /**
     * Returns the default for this option
     *
     * @return string|null
     */
    public function getDefault(): ?string;

    /**
     * Sets the default for this option
     *
     * @param string|null $default
     * @return static
     */
    public function setDefault(?string $default): static;
}