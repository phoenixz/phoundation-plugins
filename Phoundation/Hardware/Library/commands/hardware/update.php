<?php

/**
 * Command hardware search
 *
 * This command will scan for available hardware devices and register them
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Arrays;
use Plugins\Phoundation\Hardware\Devices\Devices;


CliDocumentation::setUsage('./pho hardware update');

CliDocumentation::setHelp('This command will update all options for all registered hardware devices


ARGUMENTS


[-c / --class TYPE]                     The class of hardware to scan for. If not specified, will scan for all types of
                                        hardware Must be one of "scanner,printer,webcam,biometric"');

CliDocumentation::setAutoComplete([
    'arguments' => [
        '-c,--class'  => [
            'word'   => function ($word) { return Arrays::keepMatchingValuesStartingWith(['scanner', 'printer', 'webcam', 'biometric'], $word); },
            'noword' => function ($word) { return ['scanner', 'printer' , 'webcam', 'biometric']; },
        ],
    ]
]);


// Validate arguments
$argv = ArgvValidator::new()
    ->select('-c,--class', true)->isOptional()->isInArray(['scanner', 'printer' , 'webcam', 'biometric'])
    ->validate();


// Update options for all devices
Log::action(ts('Updating all registered hardware devices'), 10);

$devices = Devices::new()->load();

foreach ($devices as $device) {
    Log::action(ts('Updating device ":name"', [
        ':name' => $device->getName()
    ]), 10);

    $device->updateOptions();
}


// Done!
Log::success(ts('Updated ":count" devices', [':count' => $devices->getCount()]), 10);
