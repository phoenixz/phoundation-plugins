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

use Phoundation\Data\DataEntries\DataEntryCore;
use Phoundation\Data\Traits\TraitStaticMethodNew;
use Phoundation\Date\Interfaces\PhoDateTimeInterface;
use Plugins\Phoundation\Firewalls\Interfaces\FirewallInterface;


class Firewall extends DataEntryCore implements FirewallInterface
{
    use TraitStaticMethodNew;


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
     * @param string                    $action         The action applied on the specified IP address
     * @param string                    $ip             The IP address on which the action was applied
     * @param PhoDateTimeInterface|null $_until  [null] If specified, this rule will be applied until the specified starting date. If not specified, the rule
     *                                                  will apply forever
     * @param PhoDateTimeInterface|null $_from   [null] If specified, this rule will be applied from the specified starting date. If not specified, the rule
     *                                                  will apply immediately
     * @param string|null               $comment [null] An optional comment about this rule
     *
     * @return $this
     */
    protected function writeRule(string $action, string $ip, ?PhoDateTimeInterface $_until, ?PhoDateTimeInterface $_from, ?string $comment = null): static
    {
        return $this->setAction($action)
                    ->setIp($ip)
                    ->setFrom($_from)
                    ->setUntil($_until)
                    ->setComment($comment)
                    ->save();
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
        $this->_firewall->deny($ip, $_until, $_from, $comment);
        return $this->writeRule('deny', $ip, $_until, $_from, $comment);
    }
}