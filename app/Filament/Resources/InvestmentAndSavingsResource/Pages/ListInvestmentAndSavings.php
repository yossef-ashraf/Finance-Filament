<?php

namespace App\Filament\Resources\InvestmentAndSavingsResource\Pages;

use App\Filament\Resources\InvestmentAndSavingsResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListInvestmentAndSavings extends ListRecords
{
    protected static string $resource = InvestmentAndSavingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('gold')
            ->hiddenLabel()
            ->icon('heroicon-m-variable')
            ->keyBindings(['shift+g'])
            ->requiresConfirmation()
            ->action(function () {
                $service = new \App\Services\GoldApiService();
                $service->handle();
                Notification::make()
                    ->title('Golds Amounts Update Success')
                    ->success()
                    ->send();
            })
            // ->
            // ->action('save')
        ];
    }
}
