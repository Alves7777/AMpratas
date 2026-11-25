<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Produto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state)))
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('category_id')
                            ->label('Categoria')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        TextInput::make('price')
                            ->label('Preço')
                            ->numeric()
                            ->prefix('R$')
                            ->required()
                            ->minValue(0),
                        TextInput::make('compare_at_price')
                            ->label('Preço riscado')
                            ->numeric()
                            ->prefix('R$')
                            ->minValue(0),
                        TextInput::make('stock')
                            ->label('Estoque')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0),
                        TextInput::make('material')
                            ->label('Material')
                            ->default('Prata 925')
                            ->maxLength(120),
                        Toggle::make('is_featured')
                            ->label('Destaque na home')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Ativo na loja')
                            ->default(true),
                    ]),
                Section::make('Descrição')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Resumo')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Descrição completa')
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
                Section::make('Fotos')
                    ->description('As imagens são salvas no MinIO (disco S3).')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Galeria')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->disk(config('filesystems.media'))
                            ->directory('products')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxFiles(8)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
