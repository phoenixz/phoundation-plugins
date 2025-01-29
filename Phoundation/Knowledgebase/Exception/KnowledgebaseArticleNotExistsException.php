<?php

/**
 * Class KnowledgebaseArticleNotExistsException
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase\Exception;

use Phoundation\Data\DataEntry\Exception\DataEntryNotExistsException;


class KnowledgebaseArticleNotExistsException extends DataEntryNotExistsException
{
}
