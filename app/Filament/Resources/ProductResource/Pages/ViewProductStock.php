<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\RelationManagers\OrderProductsRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\SkusRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProductStock extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getBreadcrumb(): string
    {
        return 'Tamaños';
    }
    
    // 🟢 CORRECCIÓN 1: Se usa el operador de coalescencia nula '??' para evitar error fatal 
    // si el objeto $this->record es nulo (es decir, el producto no se carga).
    public function getTitle(): string
    {
        return $this->record?->name ?? 'Detalle de Stock';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // CORRECCIÓN 2: Se hace la ImageEntry más segura.
                ImageEntry::make('thumb')
                    ->label('Imagen Principal')
                    ->circular()
                    ->height(80),
                    
                TextEntry::make('name')->label('Nombre')->columnSpan(2),
                
                TextEntry::make('ref')->label('Referencia'),
                
                // 🟢 CORRECCIÓN 3: Se añade ->placeholder() para que si la relación 'color' 
                // es nula, no se intente acceder a ->name, previniendo el error fatal.
                TextEntry::make('color.name')
                    ->label('Color')
                    ->placeholder('Sin color asignado'), 
                
                TextEntry::make('price')->money()->label('Precio'),
            ])->columns(8);
    }

    public function getRelationManagers(): array
    {
        // NOTA IMPORTANTE: Si las correcciones anteriores fallan, el error está 
        // dentro de estas dos clases RelationManager, ya que se ejecutan después.
        return [
            SkusRelationManager::class,
            OrderProductsRelationManager::class
        ];
    }
}