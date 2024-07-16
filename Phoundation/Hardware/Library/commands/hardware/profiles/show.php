<?php

/**
 * Command hardware/profiles/show
 *
 * This command will show the details for the specified hardware device profile
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */

declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\DataEntry\Exception\DataEntryNotExistsException;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Exception\NotExistsException;use Plugins\Phoundation\Hardware\Devices\Device;
use Plugins\Phoundation\Hardware\Devices\Devices;
use Plugins\Phoundation\Hardware\Devices\Profile;

CliDocumentation::setUsage('./pho hardware profiles show DEVICE PROFILE');

CliDocumentation::setHelp('This command will show the details of the specified hardware device


ARGUMENTS


DEVICE                                  The device name for which to show the details

PROFILE                                 The profile name for which to show the details');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) { return Devices::new()->load()->getMatchingKeys($word); },
            'noword' => function ()      { return Devices::new()->load(); },
        ],
        1 => [
            'word'   => function ($word, $arguments) { return Device::load($arguments[0])->getProfiles()->getMatchingKeys($word); },
            'noword' => function ($word, $arguments) { return Device::load($arguments[0])->getProfiles(); },
        ],
    ]
]);


// Validate arguments
$argv = ArgvValidator::new()
    ->select('device')->isVariableName()
    ->select('profile')->isName()
    ->validate();


// Find the device and profile
try {
    $device = Device::load($argv['device']);

} catch (DataEntryNotExistsException $e) {
    throw NotExistsException::new(tr('The specified device ":device" does not exist', [
        ':device'  => $argv['device'],
    ]), $e)->makeWarning();
}

try {
    $profile = $device->getProfiles()->get($argv['profile']);

} catch (NotExistsException $e) {
    throw NotExistsException::new(tr('The specified profile ":profile" does not exist for device ":device"', [
        ':device'  => $device->getDisplayName(),
        ':profile' => $argv['profile'],
    ]), $e)->makeWarning();
}


// Display the profile and its options
$profile
    ->displayCliForm()
    ->getOptions()
        ->displayCliMessage()
        ->displayCliMessage(tr('Available options'), true)
        ->displayCliTable([
            'key'     => tr('Key'),
            'value'   => tr('Value'),
            'values'  => tr('Possible values'),
            'range'   => tr('Range'),
            'default' => tr('Default'),
        ]);
