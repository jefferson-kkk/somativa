<?php

namespace App\Filament\Resources\SolicitacaoSaidas\Pages;

use App\Filament\Resources\SolicitacaoSaidas\SolicitacaoSaidaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSolicitacaoSaida extends EditRecord
{
    protected static string $resource = SolicitacaoSaidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
