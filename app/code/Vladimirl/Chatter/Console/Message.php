<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Console;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Message extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Vladimirl\Chatter\Helper\MessageGenerator $messageGenerator
     */
    protected $messageGenerator;

    /**
     * Message constructor.
     * @param \Vladimirl\Chatter\Helper\MessageGenerator $messageGenerator
     */
    public function __construct(
        \Vladimirl\Chatter\Helper\MessageGenerator $messageGenerator
    ) {
        $this->messageGenerator = $messageGenerator;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('chatter:generate-message')
            ->setDescription('Generate random message');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->messageGenerator->generateMessage();

        $output->writeln('<info>Messages was generated!</info>');
        return Cli::RETURN_SUCCESS;
    }
}
