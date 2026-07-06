<?php

namespace App\Filament\Resources;

use App\Enums\ResourceCategory;
use App\Filament\Resources\ResourceResource\Pages;
use App\Models\Resource as ResourceModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Resources';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category')
                    ->options(ResourceCategory::class)
                    ->default(ResourceCategory::Report)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                    ->collection('file')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_published')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(ResourceCategory::class),
                Tables\Filters\TernaryFilter::make('is_published'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResources::route('/'),
            'create' => Pages\CreateResource::route('/create'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }
}
