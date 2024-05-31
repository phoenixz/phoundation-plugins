<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Pages;

use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Requests\Response;

throw new \Phoundation\Exception\UnderConstructionException();

/**
 * Class TemplateLostPasswordPage
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Web
 */
class TemplateSetupPage extends TemplateRenderer
{
    public function render(): ?string
    {
        // This page will build its own body
        Response::setBuildBody(false);

        $this->render = '   ';

        return parent::render();
    }
}