<?php
declare(strict_types=1);

namespace BakeTwig\View\Helper;

use Bake\View\BakeView;
use BakeTwig\View\Template;
use BakeTwig\View\Template\FormTemplate;
use BakeTwig\View\Template\IndexTemplate;
use Cake\View\Helper;

/**
 * Twig helper
 */
class BakeTwigHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    /**
     * @param BakeView $view
     * @return string
     */
    public function index(BakeView $view): string
    {
        return (new IndexTemplate($view))->getTemplate();
    }

    /**
     * @param BakeView $view
     * @return string
     */
    public function form(BakeView $view): string
    {
        return (new FormTemplate($view))->getTemplate();
    }
}
