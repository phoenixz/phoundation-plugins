<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Layouts;

use Phoundation\Web\Html\Layouts\Layout;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateLayout
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
abstract class TemplateLayout extends TemplateRenderer
{
    /**
     * Layout class constructor
     */
    public function __construct(Layout $component)
    {
        parent::__construct($component);
    }
}