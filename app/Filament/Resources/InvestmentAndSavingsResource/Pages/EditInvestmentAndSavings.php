<?php

namespace App\Filament\Resources\InvestmentAndSavingsResource\Pages;

use App\Filament\Resources\InvestmentAndSavingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentAndSavings extends EditRecord
{
    protected static string $resource = InvestmentAndSavingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
