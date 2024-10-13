<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => PostStatusEnum::class
        ];
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255)
                ->autocomplete('off')
                ->afterStateUpdated(function ($operation, $state, $set) {
                    return $operation === 'create' ? $set('slug', Str::slug($state)) : null;
                }),
            TextInput::make('slug')
                ->label('Slug')
                ->unique(Post::class, 'slug', ignoreRecord: true)
                ->maxLength(255)
                ->disabled(),
            RichEditor::make('content')
                ->label('Content')
                ->required()
                ->columnSpanFull(),
            DatePicker::make('published_date')
                ->label('Publish Date')
                ->date(),
            Select::make('status')
                ->options(PostStatusEnum::class)
                ->required()
                ->label('Status'),

            Select::make('authors')
                ->relationship('authors', 'name')
                ->createOptionForm(Author::getForm())
                ->multiple()
                ->label('Authors'),

            Select::make('categories')
                ->relationship('categories', 'name')
                ->createOptionForm(Category::getForm())
                ->multiple()
                ->label('Categories'),

            FileUpload::make('image')
                ->label('Image')
                ->image()
                ->imageEditor()
                ->directory('post')
                ->maxSize(2040)
                ->columnSpanFull(),
        ];
    }
}
