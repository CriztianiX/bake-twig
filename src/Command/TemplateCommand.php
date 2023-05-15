<?php
declare(strict_types=1);

namespace BakeTwig\Command;

use Bake\Utility\CommonOptionsTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;

/**
 * Template command.
 */
class TemplateCommand extends Command
{
    use CommonOptionsTrait;

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $this->_setCommonOptions($parser);

        $parser->addOption('theme', [
            'short' => 't',
            'help' => 'The theme to use when baking code.',
            'default' => 'BakeTwig'
        ]);

        $parser->addArgument('name', [
            'help' => ' Name of the controller views to bake',
            'required' => true
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): int|null
    {
        $templateCommand = new \Bake\Command\TemplateCommand();

        $templateCommand->ext = 'twig';

        return $templateCommand->execute($args, $io);
    }
}
