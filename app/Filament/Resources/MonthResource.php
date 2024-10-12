<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonthResource\Pages;
use App\Filament\Resources\MonthResource\RelationManagers;
use App\Models\Month;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonthResource extends Resource
{
    protected static ?string $model = Month::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('month')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('year')
                ->required()
                ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('month'),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('total_debts')
                ->getStateUsing(fn (Month $record): float => $record->debts->sum('amount')),
            Tables\Columns\TextColumn::make('total_credits')
                ->getStateUsing(fn (Month $record): float => $record->credits->sum('amount')),
            Tables\Columns\TextColumn::make('balance')
                ->getStateUsing(fn (Month $record): float => $record->credits->sum('amount') - $record->debts->sum('amount')),Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('year')
                ->form([
                    Forms\Components\TextInput::make('year')->integer(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['year'],
                            fn (Builder $query, $year): Builder => $query->where('year', $year),
                        );
                }),
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
            // RelationManagers\DebtsRelationManager::class,
            // RelationManagers\CreditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonths::route('/'),
            'create' => Pages\CreateMonth::route('/create'),
            'edit' => Pages\EditMonth::route('/{record}/edit'),
        ];
    }
}
