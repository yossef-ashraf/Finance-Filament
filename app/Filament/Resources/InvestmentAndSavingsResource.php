<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestmentAndSavingsResource\Pages;
use App\Filament\Resources\InvestmentAndSavingsResource\RelationManagers;
use App\Models\InvestmentAndSavings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestmentAndSavingsResource extends Resource
{
    protected static ?string $model = InvestmentAndSavings::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('val')
                ->nullable(),
            Forms\Components\TextInput::make('amount')
                ->nullable(),
            Forms\Components\Textarea::make('note')
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('val')->label('Value')->sortable(),
                TextColumn::make('amount')->label('Amount')->sortable(),
                TextColumn::make('note')->label('Note'),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime(),
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
            'index' => Pages\ListInvestmentAndSavings::route('/'),
            'create' => Pages\CreateInvestmentAndSavings::route('/create'),
            'edit' => Pages\EditInvestmentAndSavings::route('/{record}/edit'),
        ];
    }
}
