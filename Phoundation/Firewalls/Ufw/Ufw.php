<?php

/**
 * Class Ufw
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Firewall
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Firewalls\Ufw;

use Phoundation\Data\Traits\TraitStaticMethodNew;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Phoundation\Exception\UnderConstructionException;
use Phoundation\Os\Processes\Interfaces\ProcessInterface;
use Phoundation\Os\Processes\Process;
use Plugins\Phoundation\Firewalls\Interfaces\FirewallInterface;


class Ufw implements FirewallInterface
{
    use TraitStaticMethodNew;


    /**
     * Tracks the firewall process object
     *
     * @var ProcessInterface $_engine
     */
    protected ProcessInterface $_engine;


    /**
     * Csf class constructor
     */
    public function __construct()
    {
throw new UnderConstructionException();
        $this->_engine = Process::new('ufw');
    }


    /**
     * Starts the firewall
     *
     * @return static
     */
    public function start(): static
    {
        // sudo iptables -t filter -F
        // sudo iptables -t filter -X

        return $this;
    }


    /**
     * Stops the firewall
     *
     * @return static
     */
    public function stop(): static
    {
        // sudo iptables -t filter -F
        // sudo iptables -t filter -X

        return $this;
    }


    /**
     * Restarts the firewall
     *
     * @return static
     */
    public function restart(): static
    {
        // sudo iptables -t filter -F
        // sudo iptables -t filter -X

        return $this;
    }


    /**
     * Will block the specified IP address for the (optionally) specified datetime range
     *
     * @param string                    $ip             The IP address to deny
     * @param PhoDateTimeInterface|null $_until  [null] If specified, this rule will be applied until the specified starting date. If not specified, the rule
     *                                                  will apply forever
     * @param PhoDateTimeInterface|null $_from   [null] If specified, this rule will be applied from the specified starting date. If not specified, the rule
     *                                                  will apply immediately
     * @param string|null               $comment [null] The optional comment to add
     *
     * @return static
     */
    public function deny(string $ip, ?PhoDateTimeInterface $_until, ?PhoDateTimeInterface $_from, ?string $comment = null): static
    {
        return $this;
    }
}