<?php

/**
 * Class Firewall
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Firewall
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Firewalls;

use Phoundation\Core\Core;
use Phoundation\Data\DataEntries\DataEntryCore;
use Phoundation\Data\DataEntries\Definitions\Definition;
use Phoundation\Data\DataEntries\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntries\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryAction;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryComments;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryFrom;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryIpAddress;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryUntil;
use Phoundation\Data\Traits\TraitStaticMethodNew;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Plugins\Phoundation\Firewalls\Csf\Csf;
use Plugins\Phoundation\Firewalls\Interfaces\FirewallInterface;
use Plugins\Phoundation\Firewalls\Ufw\Ufw;


class Firewall extends DataEntryCore implements FirewallInterface
{
    use TraitStaticMethodNew;
    use TraitDataEntryAction;
    use TraitDataEntryIpAddress;
    use TraitDataEntryFrom;
    use TraitDataEntryUntil;
    use TraitDataEntryComments;


    /**
     * Tracks the firewall implementation object
     *
     * @var FirewallInterface $_firewall
     */
    protected FirewallInterface $_firewall;


    /**
     * Csf class constructor
     */
    public function __construct()
    {
        parent::__construct();

        Core::checkProcessIsRoot();

        // Initialize the firewall engine to use
        $this->_firewall = $this->getConfigFirewallEngineObject();
    }


    /**
     * Returns the table name used by this object
     *
     * @return string|null
     */
    public static function getTable(): ?string
    {
        return 'phoundation_firewalls';
    }


    /**
     * Returns the name of this DataEntry class
     *
     * @return string
     */
    public static function getEntryName(): string
    {
        return tr('Firewall entry');
    }


    /**
     * Returns the field that is unique for this object
     *
     * @return string|null
     */
    public static function getUniqueColumn(): ?string
    {
        return null;
    }


    /**
     * Returns the identifier of the firewall engine to use
     *
     * Currently supported firewalls are:
     *
     * csf
     * ufw
     *
     * @return string
     */
    public function getConfigFirewallEngine(): string
    {
        return config()->getString('security.firewall.engine', 'csf');
    }


    /**
     * Returns the firewall engine object to use
     *
     * Currently supported firewalls are:
     *
     * csf
     * ufw
     *
     * @return FirewallInterface
     */
    public function getConfigFirewallEngineObject(): FirewallInterface
    {
        return match ($this->getConfigFirewallEngine()) {
            'csf' => new Csf(),
            'ufw' => new Ufw(),
        };
    }


    /**
     * Writes the specified firewall rule to the database
     *
     * @param string                    $action            The action applied on the specified IP address
     * @param string                    $ip_address        The IP address on which the action was applied
     * @param PhoDateTimeInterface|null $_until     [null] If specified, this rule will be applied until the specified starting date. If not specified, the rule
     *                                                     will apply forever
     * @param PhoDateTimeInterface|null $_from      [null] If specified, this rule will be applied from the specified starting date. If not specified, the rule
     *                                                     will apply immediately
     * @param string|null               $comments   [null] An optional comment about this rule
     *
     * @return $this
     */
    protected function writeRule(string $action, string $ip_address, ?PhoDateTimeInterface $_until, ?PhoDateTimeInterface $_from, ?string $comments = null): static
    {
        return $this->setAction($action)
                    ->setIpAddress($ip_address)
                    ->setFrom($_from)
                    ->setUntil($_until)
                    ->setComments($comments)
                    ->save();
    }


    /**
     * Returns the short name for the firewall
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_firewall->getName();
    }


    /**
     * Returns the full name for the firewall
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->_firewall->getFullName();
    }


    /**
     * Returns the version for the firewall
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->_firewall->getVersion();
    }


    /**
     * Installs Config Server Firewall on this host
     *
     * @return static
     */
    public function install(): static
    {
        $this->_firewall->install();
        return $this;
    }


    /**
     * Disables the firewall from starting up
     *
     * @return static
     */
    public function disable(): static
    {
        $this->_firewall->disable();
        return $this;
    }


    /**
     * Enables the firewall so it automatically starts up
     *
     * @return static
     */
    public function enable(): static
    {
        $this->_firewall->enable();
        return $this;
    }


    /**
     * Starts the firewall
     *
     * @return static
     */
    public function start(): static
    {
        $this->_firewall->start();
        return $this;
    }


    /**
     * Stops the firewall
     *
     * @return static
     */
    public function stop(): static
    {
        $this->_firewall->stop();
        return $this;
    }


    /**
     * Restarts the firewall
     *
     * @return static
     */
    public function restart(): static
    {
        $this->_firewall->restart();
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
        $this->_firewall->deny($ip_address, $_until, $_from, $comments);
        return $this->writeRule('deny', $ip_address, $_until, $_from, $comments);
    }


    /**
     * Sets the available data keys for this entry
     *
     * @param DefinitionsInterface $_definitions
     *
     * @return static
     */
    protected function setDefinitionsObject(DefinitionsInterface $_definitions): static
    {
        $_definitions->add(Definition::new())

                     ->add(DefinitionFactory::newIpAddress())

                     ->add(DefinitionFactory::newDateTime('from'))

                     ->add(DefinitionFactory::newDateTime('until'))

                     ->add(DefinitionFactory::newComments());

        return $this;
    }
}