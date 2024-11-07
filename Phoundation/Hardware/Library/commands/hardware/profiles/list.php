<?php

/**
 * Command hardware profiles list
 *
 * This command will scan for available hardware devices and register them
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Arrays;
use Phoundation\Utils\Utils;
use Plugins\Phoundation\Hardware\Devices\Devices;
use Plugins\Phoundation\Hardware\Devices\Profiles;


CliDocumentation::setUsage('./pho hardware profiles list');

CliDocumentation::setHelp('This command will list all hardware profiles registered in the database


ARGUMENTS


-');

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
    ->validate();


// List available devices
Profiles::new()->load()->displayCliTable([
    'name'  => tr('Name'),
]);
