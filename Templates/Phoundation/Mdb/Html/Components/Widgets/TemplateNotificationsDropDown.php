<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Widgets;

use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Notifications\Html\Components\Modals\NotificationModal;
use Phoundation\Utils\Strings;
use Phoundation\Web\Html\Components\Widgets\NotificationsDropDown;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateNotificationsDropDown
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateNotificationsDropDown extends TemplateRenderer
{
    /**
     * NotificationsDropDown class constructor
     */
    public function __construct(NotificationsDropDown $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the NavBar
     *
     * @return string|null
     */
    public function render(): ?string
    {
        if (!$this->component->getAllNotificationsUrl()) {
            throw new OutOfBoundsException(tr('No all notifications page URL specified'));
        }

        if (!$this->component->getNotificationsUrl()) {
            throw new OutOfBoundsException(tr('No notifications page URL specified'));
        }

        $notifications = $this->component->getNotifications();

        if ($notifications) {
            $notifications->autoUpdate();

            $count = $notifications->getCount();
            $mode = $notifications->getMostImportantMode();
            $mode = strtolower($mode);

            // With HTML, "notice" and "information" are known as "info"
            $mode = match ($mode) {
                'unknown', 'notice', 'information' => 'info',
                default => $mode
            };

        } else {
            $count = 0;
        }

        $this->render = '   <span data-mdb-dropdown-init class="nav-link dropdown-toggle hidden-arrow" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                              <i class="fas fa-bell fa-lg"></i>
                              ' . ($count ? '<span class="badge rounded-pill badge-notification bg-' . Html::safe($mode) . '">' . Html::safe($count) . '</span>' : null) . '
                            </span>
                            <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdownMenuLink">
                              <li>
                                <span class="dropdown-item">' . ($count ? tr(':count Notifications', [':count' => ($count > 99 ? '99+' : $count)]) : tr('No notifications available')) . '</span>
                              </li>
                              <li>
                                <hr class="dropdown-divider" />
                              </li>';

        if ($count) {
            $current = 0;

            foreach ($notifications as $notification) {
                if (++$current > 12) {
                    break;
                }

                $this->render .= '<li>
                                    <a class="dropdown-item" href="' . Html::safe(str_replace(':ID', (string)$notification->getId(), (string)$this->component->getNotificationsUrl())) . '">
                                        ' . $notification->getIcon()?->render() . Html::safe(Strings::truncate($notification->getTitle(), 24)) . '
                                    </a>
                                  </li>';


//                $this->render .= '<a href="" class="dropdown-item notification open-modal" data-id="' . $notification->getId() . '">
//                                    ' . ($notification->getIcon()?->render() . Html::safe(Strings::truncate($notification->getTitle(), 24)) . '
//                                    <span class="float-right text-muted text-sm"> ' . Html::safe(Date::getAge($notification->getCreatedOnObject())) . '</span>
//                                  </a>';
            }

        }

        $this->render .= '        <li>
                                    <a href="' . Html::safe($this->component->getAllNotificationsUrl()) . '" class="dropdown-item dropdown-footer">' . tr('See all unread notifications') . '</a>
                                  </li>
                                </ul>';

        return parent::render() . NotificationModal::new()->render();
    }
}
