import { Head } from '@inertiajs/react'
import React from 'react'

const MetaTag = ({ metaTag }) => {
    // 🟢 CORRECCIÓN: Implementamos seguridad contra valores nulos (null).
    // Usamos '?' (encadenamiento opcional) para acceder a las propiedades.
    // Usamos '??' (coalescencia nula) para dar un valor por defecto si la propiedad no existe o es null.
    
    // Si metaTag es null, title será 'Detalle de Producto - Mi Tienda'.
    const title = metaTag?.meta_title ?? 'Detalle de Producto - Mi Tienda';

    // Si metaTag.meta_description es null, se usará el texto de fallback.
    const description = metaTag?.meta_description ?? 'Descubre la información completa de este producto en nuestra tienda.';

    return (
        // Utilizamos las variables seguras que ya no pueden ser nulas
        <Head title={title}>
            <meta name="description" content={description} />
        </Head>
    )
}

export default MetaTag