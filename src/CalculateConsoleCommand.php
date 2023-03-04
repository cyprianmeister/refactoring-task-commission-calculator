<?php

declare(strict_types=1);

namespace App;

use App\Calculation\TransactionCalculatorInterface;
use App\Exception\ApplicationException;
use App\Input\InputProviderInterface;
use App\Transaction\TransactionDeserializable;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'calculate:transaction:commission', aliases: ['c:t:c', 'calculate'])]
class CalculateConsoleCommand extends Command
{
    private const FILE_ARGUMENT = 'inputFile';

    public function __construct(
        private readonly InputProviderInterface $inputProvider,
        private readonly TransactionCalculatorInterface $calculator,
        private readonly TransactionDeserializable $deserializer,
        private readonly ?LoggerInterface $logger = null,
    ) {
        parent::__construct();
    }

    protected function configure() : void
    {
        $this->setDescription('Calculates transactions commission.');
        $this->addArgument(self::FILE_ARGUMENT, InputArgument::REQUIRED, 'Transactions input file');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        try {
            $filePath = $input->getArgument(self::FILE_ARGUMENT);

            foreach ($this->generateResults($filePath) as $item) {
                $output->writeln((string) $item);
            }

            return Command::SUCCESS;
        } catch (ApplicationException $exception) {
            $this->logger?->error($exception->getPrevious()?->getMessage() ?? $exception->getMessage());
            $this->outputError($exception->getMessage(), $output);
        } catch (\Throwable $exception) {
            $this->logger?->critical($exception->getMessage());
            $this->outputError(ApplicationException::MESSAGE, $output);
        }

        return Command::FAILURE;
    }

    /**
     * @throws ApplicationException
     *
     * @return iterable<float|int>
     */
    private function generateResults(string $filePath) : iterable
    {
        $this->inputProvider->setSource($filePath);

        foreach ($this->inputProvider->provide() as $item) {
            if (empty(\trim($item))) {
                continue;
            }
            $transaction = $this->deserializer->deserialize($item);
            yield $this->calculator->calculate($transaction);
        }
    }

    private function outputError(string $message, OutputInterface $output) : void
    {
        $output->writeln(\sprintf('<error>%s</error>', $message));
    }
}
