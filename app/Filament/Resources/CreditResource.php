<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditResource\Pages;
use App\Filament\Resources\CreditResource\RelationManagers;
use App\Models\Credit;
use App\Models\Month;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditResource extends Resource
{
    protected static ?string $model = Credit::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
            Forms\Components\Textarea::make('note')
                ->maxLength(65535),
            Forms\Components\Select::make('type')
                ->options([
                    1=>'Salary',
                    2=>'Freelance',
                    3=>'Fix',
                    4=>'Total',
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
                Tables\Columns\TextColumn::make('amount')->sortable()
                ->icon('heroicon-m-currency-dollar')->money('EGP'),
            Tables\Columns\TextColumn::make('type')
            ->icon('heroicon-m-banknotes')
                ->formatStateUsing(fn ($record): string => match ($record->type) {
                    1 => 'Salary',
                    2 => 'Freelance',
                    3 => 'Fix',
                    4 => 'Total',
                    0 => 'Other',
                    default => 'Unknown',
                })
                ->badge()
                ->color(fn ($record): string => match ($record->type) {
                    1 => 'success',   // Salary
                    2 => 'info',  // Freelance
                    3 => 'danger',     // Fix
                    4 => 'primary',   // Total
                    0 => 'warning',   // Other
                    default => 'warning', // Unknown
                }),
                Tables\Columns\TextColumn::make('note')->sortable()  ->limit(50),
                Tables\Columns\TextColumn::make('month.month')->sortable(),
                Tables\Columns\TextColumn::make('month.year')->label('Year')->sortable(),
                // Tables\Columns\TextColumn::make('created_at')->sortable()
                //     ->dateTime(),

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
            'index' => Pages\ListCredits::route('/'),
            'create' => Pages\CreateCredit::route('/create'),
            'edit' => Pages\EditCredit::route('/{record}/edit'),
        ];
    }
}
