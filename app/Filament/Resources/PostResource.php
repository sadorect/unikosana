<?php

namespace App\Filament\Resources;

use App\Enums\PostType;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'News & Announcements';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('type')
                            ->options(PostType::class)
                            ->default(PostType::News)
                            ->required(),
                        Forms\Components\TextInput::make('category')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->helperText('Leave empty to keep as draft.'),
                        Forms\Components\SpatieTagsInput::make('tags'),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Feature on homepage'),
                    ]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('featured_image')
                    ->collection('featured_image')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('excerpt')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('body')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('meta_description')
                    ->maxLength(255)
                    ->helperText('Used for SEO / social previews.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured_image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('author.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Draft'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(PostType::class),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
