<?php

/**
 * Menu class
 *
 *
 *
 * @author Sven Olaf Oostenbrink <sven@medinet.ca>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Bookmarks
 */

declare(strict_types=1);

namespace Plugins\Phoundation\Bookmarks\Library;

class Menu extends \Phoundation\Web\Html\Components\Widgets\Menus\Menu
{
    /**
     * Menu class constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setSource([
            tr('Bookmarks') => [
                'icon' => '',
            ],
        ]);
    }
}
