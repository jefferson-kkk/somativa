<?php

namespace App\Filament\Professor\Resources\ExitRequests;

use App\Filament\Professor\Resources\ExitRequests\Pages\ListExitRequests;
use App\Models\ExitRequest;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExitRequestResource extends Resource
{
    protected static ?string $model = ExitRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Notificacoes';

    protected static ?string $modelLabel = 'notificacao';

    protected static ?string $pluralModelLabel = 'notificacoes';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->whereHas('student', fn (Builder $query): Builder => $query
                    ->where('responsavel', auth()->user()?->name))
                ->with('student')
                ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('student.nome')
                    ->label('Aluno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.turma')
                    ->label('Turma'),
                Tables\Columns\TextColumn::make('motivo_saida')
                    ->label('Motivo'),
                Tables\Columns\TextColumn::make('horario_saida')
                    ->label('Horario'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ExitRequest::whereHas('student', fn (Builder $query): Builder => $query
            ->where('responsavel', auth()->user()?->name))
            ->where('status', 'pendente')
            ->count();

        return $count ? (string) $count : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExitRequests::route('/'),
        ];
    }
}
