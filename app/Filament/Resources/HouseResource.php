<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'House';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Rumah';
    protected static ?string $pluralLabel = 'Daftar Rumah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Rumah')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('lokasi')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('harga')
                            ->label('Harga')
                            ->required()
                            ->numeric(),
                    ])->columns(3),
                Section::make('Detail Rumah')
                    ->schema([
                        TextInput::make('luas_tanah')
                            ->label('Luas Tanah (m²)')
                            ->required()
                            ->numeric(),
                        TextInput::make('luas_bangunan')
                            ->label('Luas Bangunan (m²)')
                            ->required()
                            ->numeric(),
                        TextInput::make('jarak_tempuh')
                            ->label('Jarak Tempuh (km)')
                            ->required()
                            ->numeric(),
                    ])->columns(3),
                Section::make('Fasilitas dan Akses')
                    ->schema([
                        Textarea::make('fasilitas')
                            ->label('Fasilitas')
                            ->required()
                            ->maxLength(65535),
                        Textarea::make('akses_transportasi')
                            ->label('Akses Transportasi')
                            ->required()
                            ->maxLength(65535),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Rumah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->sortable()
                    ->searchable()
                    ->money('IDR', true),
                TextColumn::make('luas_tanah')
                    ->label('Luas Tanah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('luas_bangunan')
                    ->label('Luas Bangunan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fasilitas')
                    ->label('Fasilitas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('akses_transportasi')
                    ->label('Akses Transportasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jarak_tempuh')
                    ->label('Jarak Tempuh')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
        ];
    }
}
