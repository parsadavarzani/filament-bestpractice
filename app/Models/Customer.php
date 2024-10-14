<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function getForm(): array
    {
        return [
           TextInput::make('name')
                ->required()
               ->autocomplete(false)
                ->maxLength(255),
           TextInput::make('email')
                ->email()
                ->required()
               ->autocomplete(false)
                ->maxLength(255),
           TextInput::make('phone')
                ->tel()
               ->autocomplete(false)
                ->maxLength(255),

           DatePicker::make('birthday')
                ->date(),

            FileUpload::make('photo')
                ->image()
                ->columnSpanFull()
                ->imageEditor(),
        ];
    }
}
