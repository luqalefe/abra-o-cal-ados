<?php

namespace App\Services;

use App\Models\Product;

class EstoqueImportService
{
    public function importFromPath(string $path): array
    {
        // Convert file to UTF-8 in memory (Brazilian ERPs typically export in Windows-1252/ISO-8859-1)
        $rawContent = file_get_contents($path);
        $encoding   = mb_detect_encoding($rawContent, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true) ?: 'ISO-8859-1';
        $content    = $encoding === 'UTF-8' ? $rawContent : mb_convert_encoding($rawContent, 'UTF-8', $encoding);

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        // Skip header lines (title + column names)
        fgetcsv($handle, 0, ';');
        fgetcsv($handle, 0, ';');

        $created     = 0;
        $updated     = 0;
        $deactivated = 0;
        $skipped     = 0;

        while (($line = fgetcsv($handle, 0, ';')) !== false) {
            if (count($line) < 8) {
                continue;
            }

            $code = trim($line[0] ?? '');

            if (! is_numeric($code)) {
                continue;
            }

            $name = trim($line[1] ?? '');

            $stock          = $this->parseBrNumber($line[3] ?? '0');
            $price          = $this->parseBrNumber($line[6] ?? '0');
            $priceWholesale = $this->parseBrNumber($line[7] ?? '0');

            if (empty($code) || empty($name)) {
                $skipped++;
                continue;
            }

            $stockInt  = max(0, (int) floor($stock));
            $available = $stockInt > 0;
            $existing  = Product::where('erp_code', $code)->first();

            if ($existing) {
                $wasAvailable = $existing->is_available;

                $existing->update([
                    'price'           => max(0.01, $price),
                    'price_wholesale' => $priceWholesale > 0 ? $priceWholesale : null,
                    'stock'           => $stockInt,
                    'is_available'    => $available,
                ]);

                if ($wasAvailable && ! $available) {
                    $deactivated++;
                }

                $updated++;
            } else {
                Product::create([
                    'erp_code'        => $code,
                    'name'            => $name,
                    'category_id'     => null,
                    'price'           => max(0.01, $price),
                    'price_wholesale' => $priceWholesale > 0 ? $priceWholesale : null,
                    'stock'           => $stockInt,
                    'is_available'    => $available,
                    'is_promoted'     => false,
                ]);
                $created++;
            }
        }

        fclose($handle);

        return compact('created', 'updated', 'deactivated', 'skipped');
    }

    private function parseBrNumber(string $value): float
    {
        $value    = trim($value);
        $negative = str_contains($value, '-');
        $value    = str_replace(['-', ' ', '.'], '', $value);
        $value    = str_replace(',', '.', $value);
        $parsed   = (float) $value;

        return $negative ? -$parsed : $parsed;
    }
}
