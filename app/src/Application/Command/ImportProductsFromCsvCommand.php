<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Shared\DTO\ImportResultDTO;
use App\Shared\Facade\ImporterFacadeInterface;
use App\Application\Service\Products\ProductsReaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'products:import', description: 'Synchronize products from a csv file')]
class ImportProductsFromCsvCommand extends Command
{
    public function __construct(
        private readonly ProductsReaderInterface $productsReader,
        private readonly ImporterFacadeInterface $importerFacade,
        readonly ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addOption(
            'batch-size',
            'b',
            InputOption::VALUE_OPTIONAL,
            'Number of batch size to import',
            25,
        );
        $this->addArgument(
            'file',
            InputOption::VALUE_REQUIRED,
            'Csv to import'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = (int) $input->getOption('batch-size');
        /** @var string $filePath */
        $filePath = $input->getArgument('file');

        $i = 1;
        $batch = 0;
        $productsData = [];
        $importResult = new ImportResultDTO(0, 0, []);
        /** @var array<string, string> $productData */
        foreach ($this->productsReader->read($filePath) as $productData) {
            $productsData[] = $productData;

            if (0 === $i % $batchSize) {
                $importResult = $this->handle($productsData, $importResult, $batch, $batchSize);

                $productsData = [];
                ++$batch;
            }

            ++$i;
        }

        if (count($productsData) > 0) {
            $importResult = $this->handle($productsData, $importResult, $batch, $batchSize);
        }

        $output->writeln(sprintf('<comment>Processed items: %s</comment>', $importResult->processedCount));
        $output->writeln(sprintf('<info>Successfuly imported: %s</info>', $importResult->successfulCount));
        $output->writeln(sprintf('<error>Failed to import: %s</error>', count($importResult->failed)));

        $this->printErrors($importResult, $output);


        return Command::SUCCESS;
    }

    /**
     * @param array<int, array<string, string>> $productsData
     */
    private function handle(array $productsData, ImportResultDTO $importResult, int $batch, int $batchSize): ImportResultDTO
    {
        $result = $this->importerFacade->importProducts($productsData);

        return ImportResultDTO::fromSelf(
            $importResult,
            $result->processedCount,
            $result->successfulCount,
            $result->failed,
            $batch,
            $batchSize,
        );
    }

    public function printErrors(ImportResultDTO $importResult, OutputInterface $output): void
    {
        if (0 === count($importResult->failed)) {
            return;
        }

        $table = new Table($output);
        $table
            ->setHeaderTitle('Invalid Rows')
            ->setHeaders(['Element #', 'Error Message'])
            ->setRows(
                array_map(
                    fn (int $row, array $errors) => [$row, join(' ,', $errors)],
                    array_keys($importResult->failed),
                    $importResult->failed
                ),
            );

        $table->render();
    }
}
