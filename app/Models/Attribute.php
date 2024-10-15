<?php

namespace App\Models;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Attribute')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->autocomplete(false)
                        ->maxLength(255),
                ]),

            Section::make('Attribute Values')
                ->schema([
                    Repeater::make('values')
                        ->relationship('attributeValues')
                        ->schema([
                            TextInput::make('value')
                                ->label('Value')
                                ->required()
                                ->autocomplete(false)
                                ->maxLength(255),
                        ])
                        ->label('Attribute Values')
                        ->createItemButtonLabel('Add Value'),
                ]),


        ];

    }
}
