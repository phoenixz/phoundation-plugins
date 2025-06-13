<?php

/**
 * Command tools strings characters in-table
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Cli\CliCommand;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Strings;


CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) { return sql()->listKeyValue('SHOW TABLES'); },
            'noword' => function ($word) { return sql()->listKeyValue('SHOW TABLES LIKE "%' . $word . '%"'); },
        ],
        1 => [
            'word'   => function ($word) { throw new \Phoundation\Exception\UnderConstructionException(); return sql()->listKeyValue('SHOW CREATE TABLE ``'); },
            'noword' => function ($word) { throw new \Phoundation\Exception\UnderConstructionException(); return sql()->listKeyValue('SHOW TABLES LIKE "%' . $word . '%"'); },
        ],
    ],
    'arguments' => [
        '-c,--connector' => [
            'word'   => function ($word) { throw new \Phoundation\Exception\UnderConstructionException(); },
            'noword' => function ($word) { throw new \Phoundation\Exception\UnderConstructionException(); },
        ],
        '-d,--database' => [
            'word'   => function ($word) { throw new \Phoundation\Exception\UnderConstructionException(); },
            'noword' => function ($word) { throw new \Phoundation\Exception\UnderConstructionException(); },
        ],
    ]
]);

CliDocumentation::setUsage('./pho tools strings characters in-table');

CliDocumentation::setHelp('The in-table tool will list all characters that are used in the specified table column


ARGUMENTS 


TABLE                                   The table to process

COLUMN                                  The column to process


OPTIONAL ARGUMENTS


[-c, --connector CONNECTOR]             Specifies the connector to use. Defaults to the "system" connector.

[-d, --database DATABASE]               Specifies the database to use. Defaults to the default database for the selected 
                                        connector.

[-l, --limit LIMIT]                     Specifies the maximum number of rows to process. Defaults to no limit, all rows.

[-i, --caps-insensitive]                If specified, the text be processed case insensitive.');

$argv = ArgvValidator::new()
                     ->select('table')->hasMaxCharacters(255)->isCode()
                     ->select('column')->hasMaxCharacters(255)->isCode()
                     ->select('-c,--connector', true)->isOptional('system')->hasMaxCharacters(255)->isCode()
                     ->select('-d,--database', true)->isOptional()->hasMaxCharacters(255)->isCode()
                     ->select('-l,--limit', true)->isOptional()->isInteger()->isPositive()
                     ->select('-i,--caps-insensitive')->isOptional()->isBoolean()
                     ->validate();


// Validate that connector exists


// Validate that database exists


// Validate that table exists in the database


// Validate that column exists in the table


// Build and execute the query
$query   = sql($argv['connector'])->query('SELECT `' . $argv['column'] . '` FROM ' . $argv['table'] . ($argv['limit'] ? ' LIMIT ' . $argv['limit'] : ''));
$results = [];


// Count characters
while($text = $query->fetch(0)) {
    $text = $text[$argv['column']];
    $text = trim((string) $text);

    if (empty($text)) {
        continue;
    }

    if ($argv['caps_insensitive']) {
        $text = strtolower($text);
    }

    $results = Strings::countCharacters($text, $results);
}


// Display result
CliCommand::echo(implode('', array_keys($results)));
