<?php

/**
 * Command tools isbase58
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
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Utils\Strings;


CliDocumentation::setUsage('./pho tools strings extract REGEX FILE [...FILE FILE ... FILES]');

CliDocumentation::setHelp('The extract tool allows you to extract all matches of a regex from a file or files.');

$argv = ArgvValidator::new()
                     ->select('regex')->hasMaxCharacters(255)
                     ->select('-r,--recursive')->isOptional()->isBoolean()
                     ->selectAll('files')->isOptional([PhoDirectory::newRootObject()])->sanitizeForceArray()->forEachField()->isPath()
                     ->validate();


// Define the callback
$callback = function ($_file) use ($argv, &$callback) {
    static $results = [];
show($_file->getSource());
    if ($_file->isDirectory()) {
show('IS DIR!');
        if ($argv['recursive']) {
            $_file->onFiles($callback);
        }

        return;
    }

show('IS FILE!');

    preg_match_all($argv['regex'], $_file->getContentsAsString(), $matches);

    if (empty($matches)) {
        return;
    }
showdie($matches);
    $results[] = $matches[0];
};


// Default files is the current directory
foreach ($argv['files'] as $_file) {
show($_file->getSource());
    $callback($_file);
}
show('FINISHED!');
