<?php

/**
 * Command hardware profiles copy
 *
 * This command will copy the specified profile to the specified target taking the specified option keys with it
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Arrays;
use Phoundation\Utils\Utils;
use Plugins\Phoundation\Hardware\Devices\Device;use Plugins\Phoundation\Hardware\Devices\Profile;


CliDocumentation::setUsage('./pho hardware profiles copy');

CliDocumentation::setHelp('This command will copy one hardware profile to another


ARGUMENTS


DEVICE                                  The name of the device

SOURCE                                  The source profile name. This profile must exist for the specified device

TARGET                                  The target profile name. This profile must not exist for the specified device,
                                        unless FORCE is used in which case it will be overwritten

[-k, --keys KEYS]                       A comma delimited list of device option keys that should be copied with this
                                        profile');

CliDocumentation::setAutoComplete([
    'arguments' => [
        '-k, --keys'  => [
            'word'   => function ($word) { return Arrays::keepMatchingValuesStartingWith(['scanner', 'printer', 'webcam', 'biometric'], $word); },
            'noword' => function ($word) { return ['scanner', 'printer' , 'webcam', 'biometric']; },
        ],
    ]
]);


// Validate arguments
$argv = ArgvValidator::new()
    ->select('device')->hasMaxCharacters(128)
    ->select('source')->isName()
    ->select('target')->isName()
    ->select('-k,--keys', true)->sanitizeForceArray()
    ->validate();


// Get the device and profile
$device  = Device::load($argv['device']);
$profile = Profile::find([
    'devices_id' => $device->getId(),
    'name'       => $argv['source']
]);


// Copy the profile
$copy = $profile->copy($argv['target'], $argv['keys'], FORCE);


// Done!
Log::success(tr('Created new profile ":profile" with ":count" options', [
    ':profile' => $copy->getName(),
    ':count'   => $copy->getOptions()->getCount()
]));
