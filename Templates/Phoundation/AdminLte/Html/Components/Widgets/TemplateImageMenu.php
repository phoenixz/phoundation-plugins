<?php

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Widgets;

use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateImageMenu
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateImageMenu extends TemplateRenderer
{
    /**
     * ImageMenu class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\ImageMenu $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the image menu block HTML
     *
     * @return string|null
     */
    public function render(): ?string
    {
        if (!$this->component->getImage()) {
            throw new OutOfBoundsException(tr('Cannot render ImageMenu object HTML, no image specified'));
        }
//.        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#exampleModal" style=""> Launch demo modal </button>

        $this->render = ' <div class="dropdown image-menu">
                            <a class="' . ($this->component->getMenu() ? 'dropdown-toggle ' : '') . 'd-flex align-items-center hidden-arrow"
                              href="' . ($this->component->getMenu() ? '#' : Html::safe($this->component->getUrl())) . '"
                              id="navbarDropdownMenuAvatar" aria-expanded="false"
                              ' . ($this->component->getMenu() ? 'role="button" data-mdb-toggle="dropdown"' : ($this->component->getModalSelector() ? 'data-mdb-toggle="modal" data-mdb-target="' . Html::safe($this->component->getModalSelector()) . '"' : null)) . '>';

        $this->render .= $this->component->getImage()->getHtmlElement()
            ->setHeight($this->component->getHeight())
            ->addClasses('rounded-circle')
            ->setExtra('loading="lazy"')
            ->render();

        $this->render .= '  </a>
                            <ul
                              class="dropdown-menu dropdown-menu-end"
                              aria-labelledby="navbarDropdownMenuAvatar"
                            >';

        if ($this->component->getMenu()) {
            foreach ($this->component->getMenu() as $label => $url) {
                $this->render .= '<li>
                                    <a class="dropdown-item" href="' . Html::safe($url) . '">' . Html::safe($label) . '</a>
                                  </li>';
            }

        }

        $this->render .= '      </ul>
                            </div>' . PHP_EOL;

        return parent::render();
    }
}