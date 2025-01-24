<?php

/**
 * Page article.php
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

use Plugins\Phoundation\Knowledgebase\Article;
use Phoundation\Data\Validator\Exception\ValidationFailedException;
use Phoundation\Data\Validator\GetValidator;
use Phoundation\Data\Validator\PostValidator;
use Phoundation\Security\Incidents\Exception\IncidentsException;
use Phoundation\Web\Html\Components\Input\Buttons\Button;
use Phoundation\Web\Html\Components\Widgets\BreadCrumbs;
use Phoundation\Web\Html\Components\Widgets\Cards\Card;
use Phoundation\Web\Html\Enums\EnumDisplayMode;
use Phoundation\Web\Html\Enums\EnumDisplaySize;
use Phoundation\Web\Html\Layouts\Grid;
use Phoundation\Web\Html\Layouts\GridColumn;
use Phoundation\Web\Http\Url;
use Phoundation\Web\Requests\Request;
use Phoundation\Web\Requests\Response;


// Validate GET arguments
$get = GetValidator::new()
    ->select('id')->isOptional()->isDbId()
    ->validate();


// Get the requested article and modify form design
$article = Article::new($get['id']);


// Build the "article" card
$article_card = Card::new()
    ->setCollapseSwitch(true)
    ->setMaximizeSwitch(true)
    ->setTitle($article->getDisplayName())
    ->setContent($article->getBody())
    ->setButtons(Button::new()->setValue(tr('Back'))->setAnchorUrl(Url::new('prev')));


// Build relevant links
$relevant_card = Card::new()
    ->setMode(EnumDisplayMode::info)
    ->setTitle(tr('Relevant links'))
    ->setContent('');


// Build documentation
$documentation_card = Card::new()
    ->setMode(EnumDisplayMode::info)
    ->setTitle(tr('Documentation'))
    ->setContent('<p>Soluta a rerum quia est blanditiis ipsam ut libero. Pariatur est ut qui itaque dolor nihil illo quae. Asperiores ut corporis et explicabo et. Velit perspiciatis sunt dicta maxime id nam aliquid repudiandae. Et id quod tempore.</p>
                                        <p>Debitis pariatur tempora quia dolores minus sint repellendus accusantium. Ipsam hic molestiae vel beatae modi et. Voluptate suscipit nisi fugit vel. Animi suscipit suscipit est excepturi est eos.</p>
                                        <p>Et molestias aut vitae et autem distinctio. Molestiae quod ullam a. Fugiat veniam dignissimos rem repudiandae consequuntur voluptatem. Enim dolores sunt unde sit dicta animi quod. Nesciunt nisi non ea sequi aut. Suscipit aperiam amet fugit facere dolorem qui deserunt.</p>');


// Set page meta data
Response::setPageTitle(tr('Article :article', [':article' => $article->getDisplayName()]));
Response::setHeaderTitle(tr('Article'));
Response::setHeaderSubTitle($article->getDisplayName());
Response::setBreadCrumbs(BreadCrumbs::new()->setSource([
    '/'                            => tr('Home'),
    '/Knowledgebase.html'          => tr('Knowledgebase'),
    '/knowledgebase/articles.html' => tr('Articles'),
    ''                             => $article->getDisplayName(),
]));


// Render and return the page grid
return Grid::new()
           ->addGridColumn(GridColumn::new()->addContent($article_card)->setSize(9))
           ->addGridColumn($relevant_card . $documentation_card, EnumDisplaySize::three);
