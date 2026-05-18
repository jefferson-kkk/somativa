<?php

namespace App\Filament\Resources\SolicitacaoSaidas\Pages;

use App\Filament\Resources\SolicitacaoSaidas\SolicitacaoSaidaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSolicitacaoSaidas extends ListRecords
{
    protected static string $resource = SolicitacaoSaidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
