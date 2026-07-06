<?php

namespace App\Filament\Resources;

use App\Enums\EventStatus;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('date')
                            ->required(),
                        Forms\Components\TimePicker::make('time')
                            ->seconds(false),
                        Forms\Components\TextInput::make('venue')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options(EventStatus::class)
                            ->default(EventStatus::Upcoming)
                            ->required(),
                        Forms\Components\TextInput::make('registration_link')
                            ->url()
                            ->maxLength(255)
                            ->helperText('External RSVP link (optional).')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('registration_enabled')
                            ->label('Enable on-site registration form')
                            ->live(),
                        Forms\Components\TextInput::make('capacity')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Leave blank for unlimited.')
                            ->visible(fn (Forms\Get $get) => $get('registration_enabled')),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Feature on homepage'),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Speakers')
                    ->schema([
                        Forms\Components\Repeater::make('speakers')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('title'),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add speaker')
                            ->collapsible(),
                    ]),
                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('flyer')
                            ->collection('flyer')
                            ->image()
                            ->imageEditor(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('documents')
                            ->collection('documents')
                            ->multiple()
                            ->acceptedFileTypes(['application/pdf'])
                            ->appendFiles(),
                        Forms\Components\Repeater::make('videos')
                            ->schema([
                                Forms\Components\TextInput::make('url')
                                    ->label('Video URL (YouTube / Vimeo)')
                                    ->url()
                                    ->required(),
                                Forms\Components\TextInput::make('title'),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add video'),
                    ]),
                Forms\Components\Section::make('Live Stream')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('live_stream_url')
                            ->label('Live stream embed/URL (YouTube, Facebook, etc.)')
                            ->url()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_live')
                            ->label('Currently live')
                            ->helperText('When on, a live banner + player shows on the event page.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('flyer')
                    ->collection('flyer'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(EventStatus::class),
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

    public static function getRelations(): array
    {
        return [
            EventResource\RelationManagers\RegistrationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
