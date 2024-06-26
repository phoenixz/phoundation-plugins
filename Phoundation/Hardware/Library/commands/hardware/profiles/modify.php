<?php

declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Arrays;
use Phoundation\Utils\Utils;
use Plugins\Phoundation\Hardware\Devices\Device;
use Plugins\Phoundation\Hardware\Devices\Devices;
use Plugins\Phoundation\Hardware\Devices\Profile;


/**
 * Command hardware/profiles/copy
 *
 * This command will copy the specified profile to the specified target taking the specified option keys with it
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */

CliDocumentation::setUsage('./pho hardware profiles modify DEVICE PROFILE KEY VALUE');

CliDocumentation::setHelp('This command will modify the specified profile by updating KEY to value VALUE


ARGUMENTS


DEVICE                                  The device to which the profile you want to modify belongs

PROFILE                                 The profile for the specified device you wish to modify

OPTION                                  The option for this profile that should be modified

VALUE                                   The new value this specified key should have. Options either have a range or a
                                        list of possible values. If the option has a range then the specified value
                                        should either fall within the range for this option, and if the option has a
                                        list of possible values, the specified value should be one of the possible
                                        values for this option');
CliDocumentation::setAutoComplete([
    'positions' => [
        0  => [
            'word'   => function ($word) { return Devices::new()->load()->keepMatchingKeysStartingWith($word,); },
            'noword' => function ()      { return Devices::new()->load()->getSourceKeys(); },
        ],
        1  => [
            'word'   => function ($word, $arguments) { return Device::load($arguments[0])->getProfiles()->keepMatchingKeysStartingWith($word); },
            'noword' => function ($word, $arguments) { return Device::load($arguments[0])->getProfiles()->getSourceKeys(); },
        ],
        2  => [
            'word'   => function ($word, $arguments) { return Device::load($arguments[0])->getProfiles()->get($arguments[1])->getOptions()->keepMatchingKeysStartingWith($word); },
            'noword' => function ($word, $arguments) { return Device::load($arguments[0])->getProfiles()->get($arguments[1])->getOptions()->getKeys(); },
        ],
        3  => [
            'word'   => function ($word, $arguments) {
                $values = Device::load($arguments[0])->getProfiles()->get($arguments[1])->getOptions()->getSourceKeyColumn($arguments[2], 'values');
                $values = Arrays::force($values, ',');
                return Arrays::keepMatchingValuesStartingWith($values, $word);
            },
            'noword' => function ($word, $arguments) {
                $values = Device::load($arguments[0])->getProfiles()->get($arguments[1])->getOptions()->getSourceKeyColumn($arguments[2], 'values');
                $values = Arrays::force($values, ',');
                return $values;
            },
        ],
    ]
]);


// Validate arguments
$argv = ArgvValidator::new()
    ->select('device')->hasMaxCharacters(128)
    ->select('profile')->isName()
    ->select('key')->isName()
    ->select('value')->isName()
    ->validate();


// Get the device and profile
$device  = Device::load($argv['device']);
$profile = Profile::find([
    'devices_id' => $device->getId(),
    'name'       => $argv['profile']
]);


// Update the specified option
$profile->getOptions()->get($argv['key'])->set($argv['value'])->save();


// Done!
Log::success(tr('Updated option ":key" for profile ":profile" with value ":value"', [
    ':profile' => $profile->getName(),
    ':key'     => $argv['key'],
    ':value'   => $argv['value']
]));
