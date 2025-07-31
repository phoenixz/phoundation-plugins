<?php

/**
 * Command tools strings longest
 *
 * This command will split the specified string by the given separator (defaults to ':separator') and find and print the longest string section with its size in
 * characters
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Arrays;


CliDocumentation::setUsage('./pho tools strings longest STRING
./pho tools strings longest STRING -s "-"');

CliDocumentation::setHelp(tr('This command will split the specified string by the given separator (defaults to ":separator") and find and print the longest 
string section with its size in characters 


ARGUMENTS


SOURCE (STRING)                         The source string to process


OPTIONAL ARGUMENTS


[-s, --separator] SEPARATOR             The character(s) to separate the source string on
                                        [DEFAULT ":separator"]
', [
    ':separator' => '/\s/'
]));


// Validate arguments
$argv = ArgvValidator::new()
                     ->select('source')->hasMaxCharacters(65_535)
                     ->select('-s,--separator', true)->isOptional('/\s/')->hasMaxCharacters(1024)
                     ->validate();


$source = preg_split($argv['separator'], $argv['source']);
$value  = Arrays::getLongestValue($source);

Log::cli($value . ' [' . strlen($value) . ']');
