<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

interface ProfileInterface
{
    /**
     * Returns  if this profile is the default profile or not
     *
     * @return bool|null
     */
    public function getDefault(): ?bool;

    /**
     * Sets if this profile is the default profile or not
     *
     * @param int|bool|null $default
     * @return static
     */
    public function setDefault(int|bool|null $default): static;

    /**
     * Returns the device driver options list for this profile
     *
     * @return OptionsInterface
     */
    public function getOptions(): OptionsInterface;

    /**
     * Erase this profile from the database and with it all linked hardware device options
     *
     * @return static
     */
    public function erase(): static;

    /**
     * Copy this profile to the specified target name, taking the specified option keys with it
     *
     * @param string $target
     * @param array|string $keys
     * @param bool $force
     * @return void
     */
    public function copy(string $target, array|string $keys, bool $force = false);
}
