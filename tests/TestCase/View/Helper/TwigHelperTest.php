<?php
declare(strict_types=1);

namespace BakeTwig\Test\TestCase\View\Helper;

use BakeTwig\View\Helper\BakeTwigHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * BakeTwig\View\Helper\TwigHelper Test Case
 */
class TwigHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BakeTwig\View\Helper\BakeTwigHelper
     */
    protected $Twig;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Twig = new BakeTwigHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Twig);

        parent::tearDown();
    }
}
