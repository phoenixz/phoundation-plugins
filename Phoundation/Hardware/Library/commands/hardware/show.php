<?php

/**
 * Command hardware show
 *
 * This command will show the details for the specified device
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Plugins\Phoundation\Hardware\Devices\Device;
use Plugins\Phoundation\Hardware\Devices\Devices;


CliDocumentation::setUsage('./pho hardware show DEVICE');

CliDocumentation::setHelp('This command will show the details of the specified hardware device


ARGUMENTS


DEVICE                                  The device for which to show the details');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) { return Devices::new()->load()->getMatchingKeys($word); },
            'noword' => function ($word) { return Devices::new()->load(); },
        ],
    ]
]);


// Validate arguments
$argv = ArgvValidator::new()
    ->select('device')->isOptional()->isVariable()
    ->validate();


// Show the specified device and its profiles
Device::new()->load($argv['device'])
    ->displayCliForm()
    ->getProfiles()
        ->displayCliTable();
