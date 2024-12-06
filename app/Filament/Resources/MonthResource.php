<?php

namespace App\Filament\Resources;

use App\Filament\Export\MonthResourceExporter;
use App\Filament\Resources\MonthResource\Pages\CreateMonth;
use App\Filament\Resources\MonthResource\Pages\EditMonth;
use App\Filament\Resources\MonthResource\Pages\ListMonths;
use App\Models\Month;
use App\Models\Debt;
use App\Models\Credit;
use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Filament\Actions\Exports;

class MonthResource extends Resource
{
    protected static ?string $model = Month::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Monthly Financial Report';
    protected static ?string $modelLabel = 'Financial Month';
    protected static ?string $pluralModelLabel = 'Monthly Financial Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('month')
                            ->label('Month')
                            ->options([
                                '1' => 'January', '2' => 'February', '3' => 'March', 
                                '4' => 'April', '5' => 'May', '6' => 'June', 
                                '7' => 'July', '8' => 'August', '9' => 'September', 
                                '10' => 'October', '11' => 'November', '12' => 'December'
                            ])
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('year')
                            ->label('Year')
                            ->required()
                            ->integer()
                            ->minValue(2020)
                            ->maxValue(now()->year + 1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Year and Month column
                Tables\Columns\TextColumn::make('year')
                    ->label('Year and Month')
                    ->formatStateUsing(fn ($record) => $record->month . ' ' . $record->year)
                    ->searchable()
                    ->sortable(),

                // Total Revenues
                BadgeColumn::make('total_credits')
                    ->label('Revenues')
                    ->getStateUsing(function (Month $record) {
                        $total = $record->credits->sum('amount');
                        return Number::format($total, 2) . ' EGP';
                    })
                    ->color('success')
                    ->icon('heroicon-o-arrow-trending-up'),

                // Total Expenses
                BadgeColumn::make('total_debts')
                    ->label('Expenses')
                    ->getStateUsing(function (Month $record) {
                        $total = $record->debts->sum('amount');
                        return Number::format($total, 2) . ' EGP';
                    })
                    ->color('danger')
                    ->icon('heroicon-o-arrow-trending-down'),

                // Balance and Percentage
                TextColumn::make('balance')
                    ->label('Balance')
                    ->getStateUsing(function (Month $record) {
                        $credits = $record->credits->sum('amount');
                        $debts = $record->debts->sum('amount');
                        $balance = $credits - $debts;
                        $percentage = $credits > 0 ? ($balance / $credits * 100) : 0;
                        
                        return sprintf(
                            '%.2f EGP (%.2f%%)', 
                            $balance, 
                            $percentage
                        );
                    })
                    ->color(fn ($state) => str_contains($state, '-') ? 'danger' : 'success'),

                // Expense Details
                TextColumn::make('debt_types')
                    ->label('Expense Details')
                    ->getStateUsing(function (Month $record) {
                        $debtTypes = [
                            'Liabilities' => $record->debts->where('type', 1)->sum('amount'),
                            'Investments' => $record->debts->where('type', 2)->sum('amount'),
                            'Others' => $record->debts->where('type', 0)->sum('amount')
                        ];
                        
                        return implode(' | ', array_map(
                            fn($type, $amount) => "$type: " . Number::format($amount, 2),
                            array_keys($debtTypes),
                            $debtTypes
                        ));
                    })
                    ->wrap(),
            ])
            ->filters([
                // Year filter with enhancements
                Tables\Filters\SelectFilter::make('year')
                    ->label('Year')
                    ->options(function () {
                        return Month::distinct('year')
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year');
                    }),
                
                // Balance filter
                Tables\Filters\Filter::make('balance')
                    ->form([
                        Forms\Components\Select::make('balance_type')
                            ->label('Balance Type')
                            ->options([
                                'positive' => 'Positive Balance',
                                'negative' => 'Negative Balance'
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['balance_type'], function ($q, $type) {
                            return $type === 'positive' 
                                ? $q->whereRaw('(SELECT SUM(amount) FROM credits) > (SELECT SUM(amount) FROM debts)')
                                : $q->whereRaw('(SELECT SUM(amount) FROM credits) < (SELECT SUM(amount) FROM debts)');
                        });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Financial Month Details')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
            // ->defaultSort('year', 'desc')
            // ->defaultSort('month', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMonths::route('/'),
            'create' => CreateMonth::route('/create'),
            'edit' => EditMonth::route('/{record}/edit'),
        ];
    }

    // Additional details for the detail view
    public static function getDetailView(): ?string
    {
        return 'month-details-view';
    }
    public static function getHeaderActions(): array
    {
        return [

        ];
    }
}
