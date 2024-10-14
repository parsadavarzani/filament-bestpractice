<?php

namespace App\Models;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public static function getForm(): array
    {
        return [
            Group::make()
                ->schema([
                    Section::make()
                        ->columns(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->autocomplete(false)
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($operation, $state, Set $set) {
                                    return $operation === 'create' ? $set('slug', Str::slug($state)) : null;
                                }),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->maxLength(255)
                                ->readOnly()
                                ->unique('products', 'slug', ignoreRecord: true),
                            RichEditor::make('description')
                                ->label('Description')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('images')
                                ->image()
                                ->imageEditor()
                                ->hiddenLabel()
                                ->directory('product')
                                ->disk('public')
                                ->visibility('public')
                                ->maxFiles(5)
                                ->collection('product-images')
                                ->multiple()
                        ])
                        ->collapsible(),
                    Section::make('Pricing')
                        ->columns(2)
                        ->schema([
                            TextInput::make('price')
                                ->label('Price')
                                ->required()
                                ->autocomplete(false)
                                ->numeric()
                                ->prefix('$'),
                            TextInput::make('old_price')
                                ->label('Compare At Price')
                                ->required()
                                ->autocomplete(false)
                                ->numeric(),
                            TextInput::make('cost')
                                ->label('Cost Per Item')
                                ->required()
                                ->numeric()
                                ->autocomplete(false)
                                ->prefix('$')
                                ->helperText('Customers Won\'t See This Price'),
                        ]),


                    Section::make('Inventory')
                        ->columns(2)
                        ->schema([
                            TextInput::make('sku')
                                ->label('SKU (Stock Keeping Unit)')
                                ->required()
                                ->autocomplete(false)
                                ->maxLength(255)
                                ->default(null),
                            TextInput::make('barcode')
                                ->label('Barcode (ISBN, UPC, GTIN, etc.)')
                                ->maxLength(255)
                                ->default(null),

                            TextInput::make('qty')
                                ->label('Quantity')
                                ->required()
                                ->autocomplete(false)
                                ->numeric()
                                ->default(0),
                            TextInput::make('security_stock')
                                ->label('Security Stock')
                                ->required()
                                ->autocomplete(false)
                                ->numeric()
                                ->default(0)
                                ->helperText('The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock. '),
                        ]),
                    Section::make('Shipping')
                        ->columns(2)
                        ->schema([
                            Checkbox::make('backorder')
                                ->label('This Product Can Be Returned'),

                            Checkbox::make('requires_shipping')
                                ->label('This Product Will Be Shipped'),
                        ]),
                ])
                ->columnSpan(['lg' => 2]),

            Group::make()
                ->schema([
                    Section::make('Status')
                        ->columns(2)
                        ->schema([
                            DatePicker::make('published_at')
                                ->date()
                                ->label('Availability')
                                ->default(now())
                                ->columnSpanFull()
                                ->required(),
                            Toggle::make('is_visible')
                                ->label('Visibility')
                                ->default(true)
                                ->columnSpanFull()
                                ->helperText('This Product Will Be Hidden From All Sales Channels.'),
                        ]),

                    Section::make('Associations')
                        ->columns(2)
                        ->schema([
                            Select::make('brand_id')
                                ->required()
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpanFull()
                                ->createOptionForm(Brand::getForm()),

                            Select::make('categories')
                                ->required()
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->columnSpanFull()
                                ->createOptionForm(Category::getForm()),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),

        ];
    }
}
