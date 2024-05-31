<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Components\Widgets\Menus;

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
 * @package Templates\AdminLte
 */
class TemplateMenu extends TemplateRenderer
{
    /**
     * Menu class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Menus\Menu $component)
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
        $menu_label = '';

        if (empty($source)) {
            return null;
        }

        if ($sub_menu) {
            $html = '<ul class="nav nav-treeview sub-menu-' . Html::safe($sub_menu) . '">';
        } else {
            $html = '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';
        }

        foreach ($source as $label => $entry) {
            // Build menu entry
            if (empty($entry['url']) and empty($entry['menu'])) {
                // Not a clickable menu element, just a label
                $menu_label = '<li class="nav-header">
                                   ' . (isset($entry['icon']) ? '<i class="nav-icon fas fa ' . Html::safe($entry['icon']) . '"></i>' : '') . '
                                   ' . strtoupper(Html::safe($label)) . (isset($entry['badge']) ? '<span class="right badge badge-' . Html::safe($entry['badge']['type']) . '">' . Html::safe($entry['badge']['label']) . '</span>' : '') . '
                               </li>';
            } else {
                $html .= $menu_label . '<li class="nav-item">
                                            <a href="' . Html::safe(isset_get($entry['url']) ?? '#') . '" class="nav-link">
                                                ' . (isset($entry['icon']) ? '<i class="nav-icon fas fa ' . Html::safe($entry['icon']) . '"></i>' : '') . '
                                                <p>' . Html::safe($label) . (isset($entry['menu']) ? '<i class="right fas fa-angle-left"></i>' : (isset($entry['badge']) ? '<span class="right badge badge-' . Html::safe($entry['badge']['type']) . '">' . Html::safe($entry['badge']['label']) . '</span>' : '')) . '</p>
                                            </a>';

                if (isset($entry['menu'])) {
                    $html .= $this->renderMenu($entry['menu'], ++$sub_menu);
                }

                $menu_label = '';
                $html      .= '</li>';
            }
        }

        $html .= '</ul>' . PHP_EOL;

        return $html;
    }
}
