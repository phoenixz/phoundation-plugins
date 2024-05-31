<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Layouts;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Layouts\Container;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateContainer
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateContainer extends TemplateRenderer
{
    /**
     * Container class constructor
     */
    public function __construct(Container $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the HTML for this container
     *
     * @return string|null
     */
    public function render(): ?string
    {
        return '<div class="container' . ($this->component->getTier()->value ? '-' . Html::safe($this->component->getTier()->value) : null) . '">' . Html::safe($this->component->getContent()) . '</div>';
    }
}