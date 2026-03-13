<?php

/**
 * Class IpTables
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Firewall
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Firewalls\IpTables;

use Phoundation\Core\Core;
use Phoundation\Data\Traits\TraitStaticMethodNew;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Phoundation\Exception\UnderConstructionException;
use Phoundation\Os\Processes\Interfaces\ProcessInterface;
use Phoundation\Os\Processes\Process;
use Plugins\Phoundation\Firewalls\Interfaces\FirewallInterface;


class IpTables implements FirewallInterface
{
    use TraitStaticMethodNew;


    /**
     * Tracks the firewall process object
     *
     * @var ProcessInterface $_firewall
     */
    protected ProcessInterface $_firewall;


    /**
     * Csf class constructor
     */
    public function __construct()
    {
throw new UnderConstructionException();
        Core::checkProcessIsRoot();

        //        $this->_firewall = Process::new('csf');
    }


    /**
     * Installs IP Tables on this host
     *
     * @return static
     */
    public function install(): static
    {
//        wget https://github.com/waytotheweb/scripts/raw/refs/heads/main/csf.tgz
//
//        tar -xzf csf.tgz
//
//        cd csf
//
//        sh install.sh
    }


    /**
     * Disables the firewall from starting up
     *
     * @return static
     */
    public function disable(): static
    {
throw new UnderConstructionException();


        return $this;
    }


    /**
     * Enables the firewall so it automatically starts up
     *
     * @return static
     */
    public function enable(): static
    {
throw new UnderConstructionException();

        return $this;
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
     * @param string                    $ip_address         The IP address to deny
     * @param PhoDateTimeInterface|null $_until      [null] If specified, this rule will be applied until the specified starting date. If not specified, the rule
     *                                                      will apply forever
     * @param PhoDateTimeInterface|null $_from       [null] If specified, this rule will be applied from the specified starting date. If not specified, the rule
     *                                                      will apply immediately
     * @param string|null               $comments    [null] The optional comment to add
     *
     * @return static
     */
    public function deny(string $ip_address, ?PhoDateTimeInterface $_until, ?PhoDateTimeInterface $_from, ?string $comments = null): static
    {

        return $this;
    }
}