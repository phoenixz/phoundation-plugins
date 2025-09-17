<?php

/**
 * Class Plugin
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase\Library;

use Phoundation\Web\Requests\Request;


class Plugin extends \Phoundation\Core\Plugins\Plugin
{
    /**
     * Returns the plugin description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return tr('This is the Knowledgebase plugin, it manages helpdesk articles');
    }


    /**
     * @return void
     */
    public static function start(): void
    {
        Request::getMenusObject()->getPrimaryMenu()?->appendSource(Menu::new());
    }
}
