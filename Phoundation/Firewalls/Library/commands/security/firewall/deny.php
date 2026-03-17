<?php

/**
 * Command security firewall deny IP
 *
 * This command will block the specified IP address.
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Accounts\Users\User;
use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Core;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Web\Routing\Route;
use Plugins\Phoundation\Firewalls\Firewall;
use Plugins\Phoundation\Humans\FingerPrint\FingerPrint;

CliDocumentation::setUsage('./pho security firewall deny IP');

CliDocumentation::setHelp('This command will block the specified IP address


ARGUMENTS


IP [IP, IP, ...]                        The IP addresses that will be blocked


OPTIONAL ARGUMENTS


[-c, --comments COMMENTS]               Optional comments to register with the block

[-u, --until DATE]                      Optional end date for the block');

CliDocumentation::setAutoComplete(User::getAutoComplete([
    'positions' => [
        0 => true
    ],
]));


// Validate arguments
$argv = ArgvValidator::new()
                     ->select('-u,--until', true)->isOptional()->sanitizeToDateTime()
                     ->select('-c,--comment', true)->isOptional()->hasMaxCharacters(65_535)
                     ->selectAll('ip')->sanitizeForceArray()->forEachField()->isIpAddress()
                     ->validate();


// Deny the specified IP addresses
foreach ($argv['ip'] as $ip) {
    Firewall::new()->deny($ip, $argv['until'], comments: $argv['comments']);
}
