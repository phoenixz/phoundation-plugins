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

use Phoundation\Core\Core;
use Phoundation\Data\Traits\TraitStaticMethodNew;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Os\Processes\Commands\SystemCtl;
use Phoundation\Os\Processes\Commands\Tar;
use Phoundation\Os\Processes\Commands\Wget;
use Phoundation\Os\Processes\Interfaces\ProcessInterface;
use Phoundation\Os\Processes\Process;
use Plugins\Phoundation\Firewalls\Interfaces\FirewallInterface;
use Plugins\Phoundation\Firewalls\Ufw\Ufw;


class Csf implements FirewallInterface
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
        Core::checkProcessIsRoot();

        $this->_firewall = Process::new('csf');
    }


    /**
     * Installs Config Server Firewall on this host
     *
     * @return static
     */
    public function install(): static
    {
        // Disable all other firewalls
        Ufw::new()
            ->stop()
            ->disable();

        $_directory = PhoDirectory::newTemporary();

        Wget::new($_directory)->setTarget('https://download.configserver.dev/csf.tgz');
        Tar::new()->untar($_directory->addFile('csf.tgz'), $_directory);

        Process::new()
               ->setCommand($_directory->addFile('csf/install.tgz'))
               ->executeNoReturn();

        return $this;
    }


    /**
     * Disables the firewall from starting up
     *
     * @return static
     */
    public function disable(): static
    {
        // csf -x, systemctl
        return $this->restart();
    }


    /**
     * Enables the firewall so it automatically starts up
     *
     * @return static
     */
    public function enable(): static
    {
        // csf -e, systemctl
        $this->_firewall->clearArguments()
                        ->appendArgument('-f')
                        ->executeNoReturn();

        return $this;
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
        $this->_firewall->clearArguments()
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
        $this->_firewall->clearArguments()
                        ->appendArgument('-ra')
                        ->executeNoReturn();

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
        $this->_firewall->clearArguments()
                        ->appendArguments(['-d', $ip_address, $comments]);

        return $this;
    }
}