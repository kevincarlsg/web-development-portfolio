<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected ?string $subheading = 'Luego de crear el producto podrás agregar más información como imágenes, especificaciones, etc.';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ajustar rutas de imágenes
        if (!empty($data['img']) && isset($data['img'][0]) && $data['img'][0] !== '/') {
            $data['img'] = '/' . $data['img'];
        }

        if (!empty($data['thumb']) && isset($data['thumb'][0]) && $data['thumb'][0] !== '/') {
            $data['thumb'] = '/' . $data['thumb'];
        }

        // Limpiar HTML de la descripción para que quede solo texto
        if (!empty($data['description'])) {
            $data['description'] = strip_tags($data['description']);
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Crear el producto padre sin imágenes
        $parent_product = static::getModel()::create(array_merge($data, [
            'img' => null,
            'thumb' => null,
        ]));

        // Asignar parent_id al producto real
        $data['parent_id'] = $parent_product->id;

        // Generar referencia segura aunque color_id sea null
        $parent_product_id = Str::padLeft($data['parent_id'], 4, '0');
        $color_id_padded = isset($data['color_id']) ? Str::padLeft($data['color_id'], 3, '0') : '000';
        $data['ref'] = $parent_product_id . '-' . $color_id_padded;

        // Crear el producto real con todos los datos
        return static::getModel()::create($data);
    }
}
