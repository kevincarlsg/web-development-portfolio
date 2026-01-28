<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\SpecificationsRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProductRelationship extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getBreadcrumb(): string
    {
        return 'Relaciones';
    }
    
    // 🟢 CORRECCIÓN 1: Se usa el operador de coalescencia nula '??' para evitar error fatal 
    // si el objeto $this->record es nulo.
    public function getTitle(): string
    {
        // Si $this->record existe, usa su nombre; si no, usa un texto por defecto.
        return $this->record?->name ?? 'Detalle de Producto';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // ImageEntry::make('thumb') no requiere corrección aquí, pero 
                // el archivo debe existir o Filament lo manejará.
                ImageEntry::make('thumb')
                    ->label('Imagen Principal'),
                    
                TextEntry::make('name')->label('Nombre')->columnSpan(2),
                
                TextEntry::make('ref')->label('Referencia'),
                
                // 🟢 CORRECCIÓN 2: Se añade ->placeholder() para que si la relación 'color' 
                // es nula, no se intente acceder a ->name, previniendo el error fatal.
                TextEntry::make('color.name')
                    ->label('Color')
                    ->placeholder('Sin asignar'), // Añadido valor de seguridad
                
                TextEntry::make('price')->money()->label('Precio'),
            ])->columns(8);
    }

    public function getRelationManagers(): array
    {
        // Si la página sigue en blanco después de las correcciones anteriores,
        // el error se encuentra dentro del código de estos RelationManagers.
        return [
            ImagesRelationManager::class,
            SpecificationsRelationManager::class,
        ];
    }
}