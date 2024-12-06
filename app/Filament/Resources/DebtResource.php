<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtResource\Pages;
use App\Filament\Resources\DebtResource\RelationManagers;
use App\Models\Debt;
use App\Models\Month;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('amount')
                ->required()
                ->numeric(),
                // ->mask(fn (Mask $mask) => $mask
                //     ->numeric()
                //     ->decimalPlaces(2)
                //     ->thousandsSeparator(',')
                // ),
            Forms\Components\Textarea::make('note')
                ->maxLength(65535),
            Forms\Components\Select::make('type')
                ->options([
                    1=>'Debt',
                    2=>'Investment',
                    0=>'Other',
                ])
                ->required(),
            Forms\Components\Select::make('month_id')
            ->options(Month::all()->pluck('name', 'id'))
            ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('amount')
                ->icon('heroicon-m-chart-pie')->money('EGP')->sortable(),
                Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(fn ($record): string => match ($record->type) {
                    1 => 'Debt',
                    2 => 'Investment',
                    0 => 'Other',
                    default => 'Unknown',
                })    ->badge()
                ->color(fn ($record): string => match ($record->type) {
                    1 => 'danger',
                    2  => 'success',
                    0 => 'warning',
                }),
            
                Tables\Columns\TextColumn::make('note')->icon('heroicon-m-document-currency-dollar')->sortable(),
                Tables\Columns\TextColumn::make('month.month')->sortable(),
                Tables\Columns\TextColumn::make('month.year')->label('Year')->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('month')
                ->options(Month::all()->pluck('name', 'id')),
            Tables\Filters\Filter::make('amount')
                ->form([
                    Forms\Components\TextInput::make('amount_from')->numeric(),
                    Forms\Components\TextInput::make('amount_to')->numeric(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['amount_from'],
                            fn (Builder $query, $amount): Builder => $query->where('amount', '>=', $amount),
                        )
                        ->when(
                            $data['amount_to'],
                            fn (Builder $query, $amount): Builder => $query->where('amount', '<=', $amount),
                        );
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDebts::route('/'),
            'create' => Pages\CreateDebt::route('/create'),
            'edit' => Pages\EditDebt::route('/{record}/edit'),
        ];
    }
}
