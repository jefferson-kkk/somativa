<?php

namespace App\Filament\Portaria\Resources\ExitRequests;

use App\Filament\Portaria\Resources\ExitRequests\Pages\ListExitRequests;
use App\Models\ExitRequest;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ExitRequestResource extends Resource
{
    protected static ?string $model = ExitRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-right-on-rectangle';

    protected static ?string $navigationLabel = 'Solicitacoes';

    protected static ?string $modelLabel = 'solicitacao';

    protected static ?string $pluralModelLabel = 'solicitacoes';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('student')
                ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('student.nome')
                    ->label('Aluno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.responsavel')
                    ->label('Responsavel/professor'),
                Tables\Columns\TextColumn::make('motivo_saida')
                    ->label('Motivo'),
                Tables\Columns\TextColumn::make('horario_saida')
                    ->label('Horario'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
            ])
            ->actions([
                Actions\Action::make('confirmar_saida')
                    ->label('Confirmar saida')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ExitRequest $record): bool => $record->status === 'pendente')
                    ->action(function (ExitRequest $record): void {
                        $record->update(['status' => 'concluido']);

                        $student = $record->student;
                        $professors = User::query()
                            ->where('role', 'professor')
                            ->where('name', $student?->responsavel)
                            ->get();

                        foreach ($professors as $professor) {
                            try {
                                Mail::raw(
                                    "O aluno {$student->nome} saiu da escola.\nHorario: {$record->horario_saida}\nMotivo: {$record->motivo_saida}",
                                    fn ($message) => $message
                                        ->to($professor->email)
                                        ->subject("Saida confirmada - {$student->nome}")
                                );
                            } catch (Throwable $exception) {
                                report($exception);
                            }
                        }

                        Notification::make()
                            ->title('Saida confirmada.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ExitRequest::where('status', 'pendente')->count();

        return $count ? (string) $count : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExitRequests::route('/'),
        ];
    }
}
