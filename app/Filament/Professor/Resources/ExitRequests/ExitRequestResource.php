<?php

namespace App\Filament\Professor\Resources\ExitRequests;

use App\Filament\Professor\Resources\ExitRequests\Pages\ListExitRequests;
use App\Models\ExitRequest;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;

class ExitRequestResource extends Resource
{
    protected static ?string $model = ExitRequest::class;
    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';
    
    protected static ?string $navigationLabel = 'Notificações de Saída';

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.nome')
                    ->label('Aluno')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('horario_saida')
                    ->label('Horário de Saída')
                    ->dateTime('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning',
                        'aprovado' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return ['index' => ListExitRequests::route('/')];
    }
}