<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    protected static ?string $title = 'Registrations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\TextInput::make('guests')->numeric()->default(0),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('phone')->placeholder('—'),
                Tables\Columns\TextColumn::make('guests'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $event = $this->getOwnerRecord();
                        $filename = 'registrations-' . $event->slug . '.csv';

                        return response()->streamDownload(function () use ($event) {
                            $out = fopen('php://output', 'w');
                            fputcsv($out, ['Name', 'Email', 'Phone', 'Guests', 'Notes', 'Registered At']);
                            foreach ($event->registrations()->orderBy('created_at')->get() as $r) {
                                fputcsv($out, [$r->name, $r->email, $r->phone, $r->guests, $r->notes, $r->created_at?->toDateTimeString()]);
                            }
                            fclose($out);
                        }, $filename, ['Content-Type' => 'text/csv']);
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
            ]);
    }
}
