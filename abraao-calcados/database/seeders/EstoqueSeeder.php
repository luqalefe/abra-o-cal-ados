<?php

namespace Database\Seeders;

use App\Services\EstoqueImportService;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    /**
     * Importa produtos do relatório de estoque do ERP.
     * Arquivo CSV esperado em: database/seeders/estoque.csv
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/estoque.csv');

        if (! file_exists($csvPath)) {
            $this->command->warn("Arquivo não encontrado: {$csvPath}");
            $this->command->warn("Copie o CSV do ERP para database/seeders/estoque.csv e execute novamente.");
            return;
        }

        $service   = new EstoqueImportService();
        $resultado = $service->importFromPath($csvPath);

        $this->command->info(sprintf(
            "Importação concluída: %d criados, %d atualizados, %d desativados, %d ignorados.",
            $resultado['created'],
            $resultado['updated'],
            $resultado['deactivated'],
            $resultado['skipped'],
        ));
    }
}
