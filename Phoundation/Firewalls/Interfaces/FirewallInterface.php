<?php

namespace Plugins\Phoundation\Firewalls\Interfaces;

use Phoundation\Date\Interfaces\PhoDateTimeInterface;

interface FirewallInterface
{
    /**
     * Returns the short name for the firewall
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the full name for the firewall
     *
     * @return string
     */
    public function getFullName(): string;

    /**
     * Returns the version for the firewall
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Installs Config Server Firewall on this host
     *
     * @return static
     */
    public function install(): static;

     /**
     * Disables the firewall from starting up
     *
     * @return static
     */
    public function disable(): static;

    /**
     * Enables the firewall so it automatically starts up
     *
     * @return static
     */
    public function enable(): static;

    /**
     * Starts the firewall
     *
     * @return static
     */
    public function start(): static;

    /**
     * Stops the firewall
     *
     * @return static
     */
    public function stop(): static;

   /**
     * Restarts the firewall
     *
     * @return static
     */
    public function restart(): static;

    /**
     * Will block the specified IP address for the (optionally) specified datetime range
     *
     * @param string                    $ip_address         The IP address to deny
     * @param PhoDateTimeInterface|null $_until      [null] If specified, this rule will be applied until the specified starting date. If not specified, the rule
     *                                                      will apply forever
     * @param PhoDateTimeInterface|null $_from       [null] If specified, this rule will be applied from the specified starting date. If not specified, the rule
     *                                                      will apply immediately
     * @param string|null               $comments    [null] The optional comment to add
     *
     * @return static
     */
    public function deny(string $ip_address, ?PhoDateTimeInterface $_until, ?PhoDateTimeInterface $_from, ?string $comments = null): static;
}