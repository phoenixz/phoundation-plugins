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

use Plugins\Phoundation\Firewalls\Firewall;


class IpTables extends Firewall
{
    public function restart(): static
    {
        // sudo iptables -t filter -F
        // sudo iptables -t filter -X
    }
}
