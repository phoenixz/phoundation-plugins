<?php

/**
 * Page knowledgebase/article.php
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

use Phoundation\Exception\AccessDeniedException;
use Phoundation\Web\Html\Components\AnchorBlock;
use Phoundation\Web\Html\Components\Widgets\Breadcrumbs\Breadcrumb;
use Plugins\Phoundation\Knowledgebase\Article;
use Phoundation\Data\Validator\Exception\ValidationFailedException;
use Phoundation\Data\Validator\GetValidator;
use Phoundation\Data\Validator\PostValidator;
use Phoundation\Security\Incidents\Exception\IncidentsException;
use Phoundation\Web\Html\Components\Input\Buttons\Button;
use Phoundation\Web\Html\Components\Input\Buttons\Buttons;
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
$article = Article::new()->loadThis($get['id']);


// Validate POST and submit
if (Request::isPostRequestMethod()) {
    try {
        switch (PostValidator::new()->getSubmitButton()) {
            case tr('Save'):
                // Update article
                $article->apply()->save();

                Response::getFlashMessagesObject()->addSuccess(tr('The article ":article" has been saved', [
                    ':article' => $article->getDisplayName(),
                ]));

                // Redirect away from POST
                Response::redirect(Url::new('/accounts/article+' . $article->getId() . '.html')->makeWww());

            case tr('Delete'):
                $article->delete();

                Response::getFlashMessagesObject()->addSuccess(tr('The account for article ":article" has been deleted', [
                    ':article' => $article->getDisplayName(),
                ]));

                Response::redirect();

            case tr('Undelete'):
                $article->undelete();

                Response::getFlashMessagesObject()->addSuccess(tr('The account for article ":article" has been undeleted', [
                    ':article' => $article->getDisplayName(),
                ]));

                Response::redirect();
        }

    } catch (IncidentsException | ValidationFailedException | AccessDeniedException $e) {
        // Oops! Show validation errors and remain on the page
        Response::getFlashMessagesObject()->addMessage($e);
        $article->forceApply();
    }
}


// Save button
if (!$article->getReadonly()) {
    $save = Button::new()->setContent(tr('Save'));
}


// Audit button.
if (!$article->isNew()) {
    $audit = Button::new()
                   ->setFloatRight(true)
                   ->setMode(EnumDisplayMode::information)
                   ->setAnchorUrl('/audit/meta+' . $article->getMetaId() . '.html')
                   ->setFloatRight(true)
                   ->setContent(tr('Audit'));
}


// Build the "article" form
$article_card = Card::new()
                 ->setCollapseSwitch(true)
                 ->setMaximizeSwitch(true)
                 ->setTitle(tr('Edit profile for article :name', [':name' => $article->getDisplayName()]))
                 ->setContent($article->getHtmlDataEntryFormObject())
                 ->setButtonsObject(Buttons::new()
                                           ->addButton(isset_get($save))
                                           ->addButton(tr('Back'), EnumDisplayMode::secondary, Url::newPrevious('/accounts/articles.html'), true)
                                           ->addButton(isset_get($audit))
                                           ->addButton(isset_get($delete))
                                           ->addButton(isset_get($lock))
                                           ->addButton(isset_get($impersonate)));


// Build relevant links
$o_relevant_card = Card::new()
                     ->setMode(EnumDisplayMode::info)
                     ->setTitle(tr('Relevant links'))
                     ->setContent(($article->isNew() ? '' : AnchorBlock::new(Url::new('/profiles/profile+' . $article->getId() . '.html')->makeWww(), tr('Profile page for this article')) .
                                                            AnchorBlock::new(Url::new('/accounts/password+' . $article->getId() . '.html')->makeWww(), tr('Change password for this article')) .
                                                            AnchorBlock::new(Url::new('/security/authentications.html')->makeWww()->addQueries('articles_id=' . $article->getId()), tr('Authentications for this article')) .
                                                            AnchorBlock::new(Url::new('/reports/security/incidents.html')->makeWww()->addQueries('articles_id=' . $article->getId()), tr('Security incidents for this article'))) .
                                                            hr(AnchorBlock::new(Url::new('/accounts/roles.html')->makeWww(), tr('Roles management')) .
                                                               AnchorBlock::new(Url::new('/accounts/rights.html')->makeWww(), tr('Rights management'))));


// Build documentation
$o_documentation_card = Card::new()
                          ->setMode(EnumDisplayMode::info)
                          ->setTitle(tr('Documentation'))
                          ->setContent('<p>Soluta a rerum quia est blanditiis ipsam ut libero. Pariatur est ut qui itaque dolor nihil illo quae. Asperiores ut corporis et explicabo et. Velit perspiciatis sunt dicta maxime id nam aliquid repudiandae. Et id quod tempore.</p>
                                        <p>Debitis pariatur tempora quia dolores minus sint repellendus accusantium. Ipsam hic molestiae vel beatae modi et. Voluptate suscipit nisi fugit vel. Animi suscipit suscipit est excepturi est eos.</p>
                                        <p>Et molestias aut vitae et autem distinctio. Molestiae quod ullam a. Fugiat veniam dignissimos rem repudiandae consequuntur voluptatem. Enim dolores sunt unde sit dicta animi quod. Nesciunt nisi non ea sequi aut. Suscipit aperiam amet fugit facere dolorem qui deserunt.</p>');


// Set page meta data
Response::setPageTitle(tr('Article :article', [':article' => $article->getDisplayName()]));
Response::setHeaderTitle(tr('Article'));
Response::setHeaderSubTitle($article->getDisplayName());
Response::setBreadcrumbs([
    Breadcrumb::new('/'                      , tr('Home')),
    Breadcrumb::new('/accounts/articles.html', tr('Articles')),
    Breadcrumb::new(''                       , $article->getDisplayName()),
]);


// Render and return the page grid
return Grid::new()
            ->addGridColumn(GridColumn::new()
                                      ->addContent($article_card)
                                      ->setSize(9)
                                      ->useForm(true))
            ->addGridColumn($o_relevant_card . $o_documentation_card, EnumDisplaySize::three);
