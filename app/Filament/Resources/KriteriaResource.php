<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KriteriaResource\Pages;
use App\Models\Kriteria;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\View\View;

class KriteriaResource extends Resource
{
    protected static ?string $model = Kriteria::class;
    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationGroup = 'Kriteria';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Kriteria';
    protected static ?string $pluralLabel = 'Daftar Kriteria';

    public static function getTableContentFooter(): View
    {
        return view('filament.tables.columns.total-kriteria', [
            'total' => Kriteria::sum('bobot'),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kriteria')
                    ->schema([
                        TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Kriteria'),
                        TextInput::make('kode')
                            ->required()
                            ->maxLength(255)
                            ->label('Kode Kriteria'),
                        TextInput::make('bobot')
                            ->required()
                            ->numeric()
                            ->label('Bobot Kriteria')
                            ->helperText('Bobot akan dinormalisasi otomatis agar total selalu 1'),
                        Select::make('type')
                            ->options([
                                'benefit' => 'Benefit',
                                'cost' => 'Cost',
                            ])
                            ->required()
                            ->label('Tipe Kriteria'),
                    ])->columns(3),
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
            ])
            ->contentFooter(static::getTableContentFooter());
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
