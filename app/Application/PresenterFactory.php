<?php

namespace Game\Application;

use Nette;
use Nette\Utils\Strings;


/**
 * Any presenter from \Teddy\<Presenter> can be overriden by \Game\<Presenter>
 * E.g. \Teddy\IndexModule\Presenters\HomepagePresenter can be overriden by \Teddy\IndexModule\Presenters\HomepagePresenter
 *
 * @author Petr Morávek <petr@pada.cz>
 * @author Tom Bartoň <grez.cz@gmail.com>
 */
class PresenterFactory extends Nette\Application\PresenterFactory
{

    /**
     * Formats presenter class name from its name.
     *
     * @param string $presenter
     * @return string
     */
    public function formatPresenterClass($presenter)
    {
        $class = parent::formatPresenterClass($presenter);
        $gameClass = 'Game\\' . Strings::substring($class, 6);

        if (Strings::startsWith($class, 'Teddy\\') && class_exists($gameClass)) {
            return $gameClass;
        }
        return $class;
    }

    /**
     * Formats presenter name from class name.
     *
     * @param string $class
     * @return string
     */
    public function unformatPresenterClass($class)
    {
        $presenter = parent::unformatPresenterClass($class);
        if (Strings::startsWith($class, 'Game\\') && (class_exists($origClass = 'Teddy\\' . Strings::substring($class, 5)) || $presenter === null)) {
            return parent::unformatPresenterClass($origClass);
        }
        return $presenter;
    }

}
