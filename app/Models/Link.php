<?php

namespace App\Models;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    public static function getForm()
    {
        return [
            TextInput::make('title')
                ->required()
                ->autocomplete('off')
                ->maxLength(255),
            TextInput::make('url')
                ->required()
                ->autocomplete('off')
                ->url()
                ->maxLength(255),
            RichEditor::make('description')
                ->required()
                ->columnSpanFull(),
            ColorPicker::make('color')
                ->required(),
            FileUpload::make('image')
                ->image()
                ->imageEditor()
                ->directory('link')
                ->columnSpanFull(),
        ];
    }
}
