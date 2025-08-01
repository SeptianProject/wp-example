<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kriteria;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\KriteriaResource\Pages;

class KriteriaResource extends Resource
{
    protected static ?string $model = Kriteria::class;
    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationGroup = 'Kriteria';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Kriteria';
    protected static ?string $pluralLabel = 'Daftar Kriteria';

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $totalOtherWeight = 0;
        $maxWeight = 1.0;

        if ($record) {
            $totalOtherWeight = Kriteria::where('id', '!=', $record->id)->sum('bobot');
            $maxWeight = round(1 - $totalOtherWeight, 4);

            $maxWeight = max($maxWeight, $record->bobot, 0.01);
        }

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kriteria')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Kriteria'),
                        Forms\Components\TextInput::make('kode')
                            ->required()
                            ->maxLength(255)
                            ->label('Kode Kriteria'),
                        Forms\Components\TextInput::make('bobot')
                            ->required()
                            ->numeric()
                            ->minValue(0.01)
                            ->maxValue(function () use ($maxWeight) {
                                return $maxWeight;
                            })
                            ->step(0.01)
                            ->label('Bobot Kriteria')
                            ->helperText(function () use ($totalOtherWeight) {
                                $available = round(1 - $totalOtherWeight, 4);
                                return "Sisa bobot tersedia: {$available}. Total semua bobot akan otomatis disesuaikan menjadi 1.";
                            }),
                        Forms\Components\Select::make('type')
                            ->options([
                                'benefit' => 'Benefit',
                                'cost' => 'Cost',
                            ])
                            ->required()
                            ->label('Tipe Kriteria'),
                    ])->columns(2),

                Forms\Components\View::make('filament.components.kriteria-weight-info')
                    ->visible(fn() => Kriteria::count() > 0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kriteria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode Kriteria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bobot')
                    ->label('Bobot Kriteria')
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->label('Total Bobot'),
                    ])
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Kriteria')
                    ->sortable()
                    ->searchable()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListKriterias::route('/'),
            'create' => Pages\CreateKriteria::route('/create'),
            'edit' => Pages\EditKriteria::route('/{record}/edit'),
        ];
    }
}
