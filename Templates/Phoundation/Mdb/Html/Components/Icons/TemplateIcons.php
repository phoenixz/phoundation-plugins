<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Icons;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateIcons
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateIcons extends TemplateRenderer
{
    /**
     * Icons class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Icons\Icons $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the icon HTML
     *
     * @note This render skips the parent Element class rendering for speed and simplicity
     * @return string|null
     */
    public function render(): ?string
    {
        if (preg_match('/[a-z0-9-_]*]/i', $this->component->getContent())) {
            // icon names should only have letters, numbers and dashes and underscores
            return $this->component->getContent();
        }

        return '<i class="fas fa-' . $this->component->getContent() . ($this->component->getTier()->value ? ' fa-' . Html::safe($this->component->getTier()->value) : '') .'"></i>';
    }
}