<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_code')
                    ->label('Cód. ERP')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Estoque')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('price')
                    ->label('Preço Varejo')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('price_wholesale')
                    ->label('Preço Atacado')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_available')
                    ->label('Disponível')
                    ->boolean()
                    ->alignCenter(),
                ToggleColumn::make('is_promoted')
                    ->label('Promoção'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Categoria'),
                TernaryFilter::make('is_available')
                    ->label('Disponibilidade')
                    ->trueLabel('Disponíveis')
                    ->falseLabel('Indisponíveis'),
                SelectFilter::make('is_promoted')
                    ->options([
                        '1' => 'Em Promoção',
                        '0' => 'Normal',
                    ])
                    ->label('Promoção'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
