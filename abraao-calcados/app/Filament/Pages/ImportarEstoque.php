<?php

namespace App\Filament\Pages;

use App\Services\EstoqueImportService;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImportarEstoque extends Page
{
    protected string $view = 'filament.pages.importar-estoque';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static ?string $navigationLabel = 'Importar Estoque';

    protected static ?string $title = 'Importar Estoque (CSV)';

    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public ?array $resultado = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('csv_file')
                    ->label('Arquivo CSV do ERP')
                    ->helperText('Relatório de Saldo de Estoque exportado do sistema ERP (formato CSV, separador ponto-e-vírgula)')
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                    ->maxSize(5120)
                    ->disk('local')
                    ->directory('estoque-imports')
                    ->visibility('private')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function importar(): void
    {
        $this->validate();

        $file = $this->data['csv_file'] ?? null;

        if (! $file) {
            Notification::make()
                ->title('Nenhum arquivo selecionado.')
                ->danger()
                ->send();
            return;
        }

        $path = $this->resolveFilePath($file);

        if (! $path || ! file_exists($path)) {
            Notification::make()
                ->title('Não foi possível acessar o arquivo enviado.')
                ->danger()
                ->send();
            return;
        }

        $service = new EstoqueImportService();
        $this->resultado = $service->importFromPath($path);

        $total = $this->resultado['created'] + $this->resultado['updated'];

        Notification::make()
            ->title("Importação concluída: {$total} produtos processados.")
            ->success()
            ->send();

        $this->form->fill();
    }

    private function resolveFilePath(mixed $file): ?string
    {
        if ($file instanceof TemporaryUploadedFile) {
            return $file->getRealPath();
        }

        if (is_string($file)) {
            $localPath = Storage::disk('local')->path($file);
            if (file_exists($localPath)) {
                return $localPath;
            }

            $livewireDisk = config('livewire.temporary_file_upload.disk') ?: config('filesystems.default', 'local');
            $tmpPath = Storage::disk($livewireDisk)->path($file);
            if (file_exists($tmpPath)) {
                return $tmpPath;
            }
        }

        // Filament 5 / Livewire 3: array serializado [ uuid => [ ClassName => '/tmp/phpXXX' ] ]
        if (is_array($file)) {
            foreach ($file as $value) {
                if ($value instanceof TemporaryUploadedFile) {
                    return $value->getRealPath();
                }
                if (is_array($value)) {
                    foreach ($value as $path) {
                        if (is_string($path) && file_exists($path)) {
                            return $path;
                        }
                    }
                }
            }
        }

        return null;
    }
}
