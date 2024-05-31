<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets\Menus;

use Phoundation\Web\Html\Components\Widgets\Menus\Interfaces\MenuInterface;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateMenu
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateMenu extends TemplateRenderer
{
    /**
     * Menu class constructor
     */
    public function __construct(MenuInterface $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders the HTML for the menu
     *
     * @todo Add caching of the menu structure
     * @return string|null
     */
    public function render(): ?string
    {
        return $this->renderMenu($this->component->getSource(), 0);
    }


    /**
     * Renders the HTML for the sidebar menu
     *
     * @param array $source
     * @param int $sub_menu
     * @return string|null
     */
    protected function renderMenu(array $source, int $sub_menu): ?string
    {
        $li = true;

        if (empty($source)) {
            return null;
        }

        if ($sub_menu) {
            $render = '<ul class="sidenav-collapse">';

        } else {
            $render = '<ul id="scroll-container" class="sidenav-menu px-2 pb-5">';
        }

        foreach ($source as $label => $entry) {
            // Build menu entry
            if (empty($entry['url']) and empty($entry['menu'])) {
                // Not a clickable menu element, just a label
                $li      = false;
                $render .= '<li class="sidenav-item pt-3">
                               ' . (isset($entry['icon']) ? '<i class="me-3 ' . Html::safe($entry['icon']) . '"></i>' : '') .
                               '<span class="sidenav-subheading text-muted">' . strtoupper(Html::safe($label)) . '</span>' . (isset($entry['badge']) ? '<span class="badge rounded-pill badge-notification bg-' . Html::safe($entry['badge']['type']) . '">' . Html::safe($entry['badge']['label']) . '</span>' : '');
            } else {
                $render .= ($li ? ' <li class="sidenav-item">' : '') . '
                                      <a href="' . Html::safe(isset_get($entry['url']) ?? '#') . '" class="sidenav-link">
                                        ' . (isset($entry['icon']) ? '<i class="me-3 ' . Html::safe($entry['icon']) . '"></i>' . (isset($entry['badge']) ? '<span class="badge rounded-pill badge-notification bg-' . Html::safe($entry['badge']['type']) . '">' . Html::safe($entry['badge']['label']) . '</span>' : '') : '') . '
                                        ' . $label . '
                                      </a>';

                if (isset($entry['menu'])) {
                    $render .= $this->renderMenu($entry['menu'], ++$sub_menu);
                }

                $li      = true;
                $render .= '</li>';
            }
        }

        $render .= '</ul>' . PHP_EOL;

        return $render;
    }
}
