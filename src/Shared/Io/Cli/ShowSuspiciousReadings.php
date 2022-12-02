<?php

namespace Holaluz\Trial\Shared\Io\Cli;

use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Holaluz\Trial\Readings\Application\DTO\ReadingResponse;
use Holaluz\Trial\Readings\UseCase\GetSuspiciousReadingsCommand;

class ShowSuspiciousReadings extends Command
{
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('holaluz:trial:run')
            ->addArgument('sourceName', InputArgument::REQUIRED, 'Source filename')
            ->setDescription('Execute Holaluz trial: show suspicious reading from a given input file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getArgument('sourceName');
        if (!is_string($filename)) {
            $output->writeln(sprintf('We expect a value of type string as source filename. %s given', gettype($filename)));
            return 2;
        }
        try {
            /** @var ReadingResponse[] $readingResponses */
            $readingResponses = $this->commandBus->handle(new GetSuspiciousReadingsCommand($filename));
        }catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 1;
        }
        $output->writeln([
            '| Client              | Month              | Suspicious         | Median',
            ' -------------------------------------------------------------------------------',
        ]);
        foreach ($readingResponses as $readingResponse) {
            $output->writeln(
                sprintf(
                    '| %s       | %s                 | %s                | %s',
                    $readingResponse->getClient(),
                    $readingResponse->getPeriod(),
                    $readingResponse->isSuspicious() ? 'Yes' : 'No',
                    $readingResponse->getReading()
                )
            );
        }
        return 0;
    }

}