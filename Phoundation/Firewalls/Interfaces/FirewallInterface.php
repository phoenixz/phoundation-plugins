<?php

namespace Plugins\Phoundation\Firewalls\Interfaces;

use Phoundation\Date\Interfaces\PhoDateTimeInterface;

interface FirewallInterface
{
    /**
     * Will block the specified IP address for the (optionally) specified datetime range
     *
     * @param string                      $ip             The IP address to deny
     * @param PhoDateTimeInterface|null   $_until  [null] If specified, this rule will be applied until the specified starting date. If not specified, the rule
     * *                                                  will apply forever
     * * @param PhoDateTimeInterface|null $_from   [null] If specified, this rule will be applied from the specified starting date. If not specified, the rule
     * *                                                  will apply immediately
     * @param string|null                 $comment [null] The optional comment to add
     *
     * @return static
     */
    public function deny(string $ip, ?PhoDateTimeInterface $_until, ?PhoDateTimeInterface $_from, ?string $comment = null): static;

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
}