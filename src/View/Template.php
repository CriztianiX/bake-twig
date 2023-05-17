<?php

declare(strict_types=1);

namespace BakeTwig\View;

use Bake\View\BakeView;
use Bake\View\Helper\BakeHelper;

abstract class Template {
    protected array $viewVars = [];

    protected BakeHelper $Bake;

    /**
     * @return string
     */
    abstract function getTemplate(): string;

    /**
     * @param BakeView $view
     */
    public function __construct(private BakeView $view)
    {
        $this->Bake = new BakeHelper($this->view);

        $varNames = $view->getVars();

        foreach ($varNames as $varName) {
            $this->viewVars[$varName] = $view->get($varName);
        }
    }
}
