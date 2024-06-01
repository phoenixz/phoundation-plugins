<?php

/**
 * Class TemplateButton
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input\Buttons;

use Phoundation\Web\Html\Components\Input\Buttons\Button;
use Phoundation\Web\Html\Template\TemplateRenderer;

class TemplateButton extends TemplateRenderer
{
    /**
     * Button class constructor
     */
    public function __construct(Button $component)
    {
        parent::__construct($component);
    }
}
