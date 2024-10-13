<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->autocomplete('off')
                ->maxLength(255)
                ->afterStateUpdated(function ($operation, $state, Set $set) {
                    return $operation === 'create' ? $set('slug', Str::slug($state)) : null;
                }),
            TextInput::make('slug')
                ->label('Slug')
                ->maxLength(255)
                ->readOnly()
                ->unique('categories', 'slug', ignoreRecord: true),

            Toggle::make('is_visible')
                ->required()
                ->label('Visibility')
        ];
    }
}
