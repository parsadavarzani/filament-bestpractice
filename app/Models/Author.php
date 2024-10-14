<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public static function getForm(): array
    {

        return [
            TextInput::make('name')
                ->label('Name')
                ->autocomplete('off')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email')
                ->autocomplete('off')
                ->email()
                ->required()
                ->unique('authors', 'email', ignoreRecord: true)
                ->maxLength(255),
            RichEditor::make('bio')
                ->label('Bio')
                ->columnSpanFull(),
            TextInput::make('github_handle')
                ->label('Github Handle')
                ->autocomplete('off')
                ->maxLength(255),
            TextInput::make('twitter_handle')
                ->label('Twitter Handle')
                ->autocomplete('off')
                ->maxLength(255),
            FileUpload::make('photo')
                ->label('Photo')
                ->image()
                ->imageEditor()
                ->directory('author')
                ->columnSpanFull(),
        ];

    }
}
