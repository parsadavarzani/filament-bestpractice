<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getForm()
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->columnSpanFull()
                ->autocomplete(false)
                ->maxLength(255),
            RichEditor::make('description')
                ->label('Description')
                ->required()
                ->columnSpanFull(),
            FileUpload::make('logo')
                ->label('Logo')
                ->image()
                ->imageEditor()
                ->directory('brand')
                ->columnSpanFull(),
        ];
    }
}
