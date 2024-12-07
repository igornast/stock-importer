<?php

declare(strict_types=1);

namespace App\Shared\DTO;

readonly class ImportResultDTO
{
    /**
     * @param array<int, array<int, string>> $failed
     */
    public function __construct(
        public int $processedCount,
        public int $successfulCount,
        public array $failed,
    ) {
    }

    /**
     * @param array<int, array<int, string>> $failed
     */
    public static function fromSelf(self $importResult, int $processed, int $successful, array $failed, int $batch, int $bathSize): ImportResultDTO
    {
        $failedRowNumbers =  array_keys($failed);
        $failedRowNumbers = array_map(fn (int $key) => ($batch * $bathSize) + $key, $failedRowNumbers);
        $failedRows = array_combine($failedRowNumbers, $failed);

        return new self(
            $importResult->processedCount + $processed,
            $importResult->successfulCount + $successful,
            $importResult->failed + $failedRows
        );
    }
}
