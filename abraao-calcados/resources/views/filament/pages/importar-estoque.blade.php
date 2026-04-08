<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Instruções --}}
        <x-filament::section>
            <x-slot name="heading">Como usar</x-slot>

            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                <p>1. Exporte o <strong>Relatório de Saldo de Estoque (Inventário)</strong> do ERP no formato CSV.</p>
                <p>2. Faça o upload do arquivo abaixo.</p>
                <p>3. O sistema irá:</p>
                <ul class="list-disc ml-5 space-y-1 mt-1">
                    <li>Atualizar o estoque e preços dos produtos já cadastrados (identificados pelo código ERP).</li>
                    <li>Criar automaticamente os produtos novos que não existirem no sistema.</li>
                    <li><strong>Desativar automaticamente</strong> os produtos com estoque zerado ou negativo.</li>
                </ul>
            </div>
        </x-filament::section>

        {{-- Formulário de upload --}}
        <x-filament::section>
            <x-slot name="heading">Upload do CSV</x-slot>

            <form wire:submit="importar">
                {{ $this->form }}

                <div class="mt-4">
                    <x-filament::button type="submit" color="primary" icon="heroicon-o-arrow-up-tray">
                        Processar Importação
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Resultado --}}
        @if ($resultado !== null)
            <x-filament::section>
                <x-slot name="heading">Resultado da Importação</x-slot>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-lg bg-green-50 dark:bg-green-950 p-4 text-center">
                        <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                            {{ $resultado['created'] }}
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 mt-1">Criados</div>
                    </div>

                    <div class="rounded-lg bg-blue-50 dark:bg-blue-950 p-4 text-center">
                        <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                            {{ $resultado['updated'] }}
                        </div>
                        <div class="text-sm text-blue-600 dark:text-blue-400 mt-1">Atualizados</div>
                    </div>

                    <div class="rounded-lg bg-amber-50 dark:bg-amber-950 p-4 text-center">
                        <div class="text-2xl font-bold text-amber-700 dark:text-amber-300">
                            {{ $resultado['deactivated'] }}
                        </div>
                        <div class="text-sm text-amber-600 dark:text-amber-400 mt-1">Desativados (sem estoque)</div>
                    </div>

                    <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-4 text-center">
                        <div class="text-2xl font-bold text-gray-700 dark:text-gray-300">
                            {{ $resultado['skipped'] }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ignorados</div>
                    </div>
                </div>

                @if ($resultado['deactivated'] > 0)
                    <p class="mt-3 text-sm text-amber-600 dark:text-amber-400">
                        ⚠️ {{ $resultado['deactivated'] }} produto(s) foram desativados automaticamente por estarem sem estoque.
                    </p>
                @endif
            </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>
