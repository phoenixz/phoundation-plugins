<?php

declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Arrays;
use Phoundation\Utils\Utils;
use Plugins\Phoundation\Hardware\Devices\Devices;


/**
 * Script hardware/list
 *
 * This command will scan for available hardware devices and register them
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */

CliDocumentation::usage('./pho hardware list');

CliDocumentation::help('This command will list all hardware devices registered in the database


ARGUMENTS


[-c / --class TYPE]                     The class of hardware that should be listed. If not specified, will list all  
                                        types of hardware. Must be one of "scanner,printer,webcam,biometric"');

CliDocumentation::autoComplete([
    'arguments' => [
        '-c,--class'  => [
            'word'   => function ($word) { return Arrays::getMatches(['scanner', 'printer' , 'webcam', 'biometric'], $word, Utils::MATCH_ALL | Utils::MATCH_BEGIN | Utils::MATCH_NO_CASE); },
            'noword' => function ()      { return ['scanner', 'printer' , 'webcam', 'biometric']; },
        ],
    ]
]);


// Validate arguments
$argv = ArgvValidator::new()
    ->select('-c,--class', true)->isOptional()->isInArray(['scanner', 'printer' , 'webcam', 'biometric'])
    ->validate();


// List available devices
Devices::new()->load()->displayCliTable([
    'class' => tr('Device class'),
    'name'  => tr('Name'),
    'model' => tr('Model'),
    'url'   => tr('URL')
]);
