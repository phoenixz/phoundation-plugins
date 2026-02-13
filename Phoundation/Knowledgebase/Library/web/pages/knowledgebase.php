<?php

/**
 * Page knowledgebase.php
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

use Phoundation\Web\Html\Components\AnchorBlock;
use Phoundation\Web\Html\Components\Widgets\Breadcrumbs\Breadcrumb;
use Plugins\Phoundation\Knowledgebase\Articles;
use Phoundation\Web\Html\Components\Widgets\Cards\Card;
use Phoundation\Web\Html\Enums\EnumDisplayMode;
use Phoundation\Web\Html\Enums\EnumDisplaySize;
use Phoundation\Web\Html\Enums\EnumHttpRequestMethod;
use Phoundation\Web\Html\Layouts\Grid;
use Phoundation\Web\Http\Url;
use Phoundation\Web\Requests\Response;


// Get the articles list and apply filters
$articles = Articles::new();
$builder  = $articles->getQueryBuilderObject()
    ->addSelect(' `knowledgebase_articles`.`id`, 
                  `knowledgebase_articles`.`name`, 
                  `knowledgebase_articles`.`status`,')
    ->addGroupBy('`knowledgebase_articles`.`id`');


// Build articles table card
$articles_card = Card::new()
    ->setTitle('Active articles')
    ->setSwitches('reload')
    ->setContent($articles->load()
                          ->getHtmlTableObject([
                              'name' => tr('Name'),
                          ])
                          ->setRowUrls('/knowledgebase/article+:ROW.html')
                          ->setComponentEmptyLabel(tr('No articles available')))
    ->useForm(true);


$articles_card->getFormObject()
              ->setAction(Url::newCurrent())
              ->setRequestMethod(EnumHttpRequestMethod::post);


// Build relevant links
$_relevant_card = Card::new()
                     ->setMode(EnumDisplayMode::info)
                     ->setTitle(tr('Relevant links'))
                     ->setContent(AnchorBlock::new(Url::new('/knowledgebase/articles.html')->makeWww(), tr('Knowledgebase')));


// Build documentation
$_documentation_card = Card::new()
                          ->setMode(EnumDisplayMode::info)
                          ->setTitle(tr('Documentation'))
                          ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');


// Set page meta-data
Response::setHeaderTitle(tr('Articles'));
Response::setBreadcrumbs([
    Breadcrumb::new('/', tr('Home')),
    Breadcrumb::new('' , tr('Knowledgebase')),
]);


// Render and return the page grid
return Grid::new()
           ->addGridColumn($articles_card                      , EnumDisplaySize::nine)
           ->addGridColumn($_relevant_card . $_documentation_card, EnumDisplaySize::three);
