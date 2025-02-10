<?php

/**
 * Class Menu
 *
 *
 *
 * @see       \Phoundation\Data\DataEntries\DataEntry
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase\Library;


class Menu extends \Phoundation\Web\Html\Components\Widgets\Menus\Menu
{
    /**
     * Menu class constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setSource([
            tr('Knowledgebase') => [
                'icon' => '',
            ],
            tr('articles') => [
                'rights' => 'knowledgebase',
                'url'    => 'knowledgebase.html',
                'icon'   => 'fas fa-users'
            ],
        ]);
    }
}
