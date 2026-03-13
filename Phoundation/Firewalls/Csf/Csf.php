<?php

/**
 * Class Csf
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Firewall
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Firewalls\Csf;

use Phoundation\Data\Traits\TraitStaticMethodNew;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Phoundation\Os\Processes\Interfaces\ProcessInterface;
use Phoundation\Os\Processes\Process;
use Plugins\Phoundation\Firewalls\Interfaces\FirewallInterface;


class Csf implements FirewallInterface
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
        $this->_engine = Process::new('csf');
    }


    /**
     * Installs Config Server Firewall on this host
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
     * Starts the firewall
     *
     * @return static
     */
    public function start(): static
    {
        return $this->restart();
    }


    /**
     * Stops the firewall
     *
     * @return static
     */
    public function stop(): static
    {
        $this->_engine->clearArguments()
                      ->appendArgument('-f')
                      ->executeNoReturn();

        return $this;
    }


    /**
     * Restarts the firewall
     *
     * @return static
     */
    public function restart(): static
    {
        $this->_engine->clearArguments()
                      ->appendArgument('-ra')
                      ->executeNoReturn();

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
        $this->_engine->clearArguments()
                      ->appendArguments(['-d', $ip, $comment]);

        return $this;
    }
}