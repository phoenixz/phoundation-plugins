<?php

/**
 * Class Plugin
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <sven@medinet.ca>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Bookmarks
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Bookmarks\Library;

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
        return tr('This plugin contains all functionalities specific to Bookmarks');
    }


    /**
     * @return void
     */
    public static function start(): void
    {
        Request::getMenusObject()->getPrimaryMenu()?->appendSource(Menu::new());
    }
}
