<?php

namespace Creode\NovaPageBuilder\Abstracts;

use Illuminate\Support\Facades\Event;
use Creode\NovaPageBuilder\Events\PageContentEvent;
use Creode\NovaPageBuilder\Events\PageContentBlockViewsEvent;

/**
 * Page Block class used to define a Page Block.
 */
abstract class PageBlockAbstract
{
    /**
     * Label of the Page Block.
     *
     * @var string
     */
    protected $label = '';

    /**
     * Name of the page block.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Any fields associated with the Page Block.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Blade view associated with the Page Block.
     *
     * @var string
     */
    protected $view = '';

    /**
     * Constructor for class.
     */
    public function __construct()
    {
        $this->setFields();
        $this->registerLayout();
        $this->registerView();
    }

    /**
     * Define a set of fields for this block.
     *
     * @return void
     */
    abstract protected function fields();

    /**
     * Sets fields for this block.
     *
     * @return void
     */
    private function setFields()
    {
        $this->fields = $this->fields();
    }

    /**
     * Registers the layout with the Page Builder.
     *
     * @return void
     */
    private function registerLayout()
    {
        Event::listen(
            function (PageContentEvent $pageContentEvent) {
                $pageContentEvent->content->addLayout(
                    $this->label,
                    $this->name,
                    $this->fields
                );
            }
        );
    }

    /**
     * Registers the view with the page builder.
     *
     * @return void
     */
    private function registerView()
    {
        Event::listen(
            function (PageContentBlockViewsEvent $PageContentBlockViewsEvent) {
                $PageContentBlockViewsEvent->views[$this->name] = $this->view;
            }
        );
    }
}