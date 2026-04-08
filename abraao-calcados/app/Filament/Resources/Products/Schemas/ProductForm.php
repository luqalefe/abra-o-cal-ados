<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Sem categoria'),
                TextInput::make('erp_code')
                    ->label('Código ERP')
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->helperText('Código do produto no sistema ERP (preenchido automaticamente na importação)'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('Preço Varejo')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                TextInput::make('price_wholesale')
                    ->label('Preço Atacado')
                    ->numeric()
                    ->prefix('R$'),
                TextInput::make('stock')
                    ->label('Estoque')
                    ->numeric()
                    ->integer()
                    ->default(0)
                    ->minValue(0),
                FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->maxFiles(5)
                    ->reorderable()
                    ->appendFiles()
                    ->disk('public')
                    ->directory('products'),
                Toggle::make('is_available')
                    ->label('Disponível no site')
                    ->default(true)
                    ->helperText('Desativado automaticamente quando o estoque chega a zero via importação'),
                Toggle::make('is_promoted')
                    ->label('Em promoção')
                    ->required(),
            ]);
    }
}
