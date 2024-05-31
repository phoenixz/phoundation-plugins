<?php

declare(strict_types=1);

namespace Templates\None;

use Phoundation\Web\Requests\Response;


/**
 * class TemplatePage
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\None
 */
class TemplatePage extends \Phoundation\Web\Html\Template\TemplatePage
{
    /**
     * Execute, builds and returns the page output according to the template.
     *
     * Either use the default execution steps from parent::execute($target), or write your own execution steps here.
     * Once the output has been generated, it should be returned.
     *
     * @return string|null
     */
    public function execute(): ?string
    {
        return $this->renderBody();
    }


    /**
     * Build the HTTP headers for the page
     *
     * @param string $output
     * @return void
     *
     */
    public function renderHttpHeaders(string $output): void
    {
        Response::setContentType('text/html');
        Response::setDoctype('html');
    }


    /**
     * Build the page header
     *
     * @return string|null
     */
    public function buildPageHeader(): ?string
    {
        return '';
    }


    /**
     * Build the page footer
     *
     * @return string|null
     */
    public function buildPageFooter(): ?string
    {
        return '';
    }


    /**
     * Builds and returns the top panel HTML
     *
     * @param string $panel
     * @return string|null
     */
    protected function buildTopPanel(string $panel): ?string
    {
        return '';
    }


    /**
     * Builds and returns the sidebar HTML
     *
     * @return string|null
     */
    protected function buildSidePanel(): ?string
    {
        return '';
    }


    /**
     * Builds the body header
     *
     * @return string|null
     */
    protected function buildBodyHeader(): ?string
    {
        return '';
    }
}
