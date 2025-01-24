<?php

/**
 * Command tools sleep
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Cli\CliCommand;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Strings;


CliDocumentation::setUsage('./pho tools sleep 4
./pho tools sleep 20');

CliDocumentation::setHelp('The sleep tool script will sleep for the specified amount of seconds');


// Get the arguments
$argv = ArgvValidator::new()
                     ->select('seconds')->isInteger()->isPositive()
                     ->validate();


// Sleep
sleep($argv['seconds']);
