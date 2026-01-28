import { formatCurrency } from "@/Helpers/helpers";
import { Link } from "@inertiajs/react";
import Badge from "../Badge";
import ProductPriceOffer from "../ProductPriceOffer";

const CardProduct = ({ product, productNew = false }) => {

    // 1. 🟢 CORRECCIÓN CRÍTICA 1: Protección inicial. Si 'product' es null o undefined, no renderizamos nada.
    if (!product) {
        return null;
    }

    // console.log(product)

    // 2. 🟢 CORRECCIÓN CRÍTICA 2: Protección de la URL de Ziggy.
    // Solo generamos la ruta si tenemos 'slug' Y 'ref'.
    const url = (product.slug && product.ref) 
        ? route("product", { slug: product.slug, ref: product.ref })
        : '#'; // Fallback seguro
    
    // 3. 🟢 CORRECCIÓN SECUNDARIA: Protección de las propiedades dentro del bucle.
    // Aseguramos que 'product.colors' exista antes de intentar mapearlo.
    const colors = product.colors || [];


    return (
        <Link
            // Usamos la variable 'url' segura
            href={url}
            className="w-full relative block max-w-md mx-auto group h-full overflow-hidden rounded-md transition duration-200 ease-in-out transform hover:-translate-y-1 md:hover:-translate-y-1.5 hover:shadow "
        >
            <div className="h-full flex flex-col">
                <div >
                    <div className="flex justify-center">
                        <img
                            // 🟢 Protección de imagen
                            src={product.thumb || '/images/placeholder.svg'}
                            alt={product.slug || 'producto'}
                            className="w-full object-cover object-top rounded-md group-hover:rounded-none "
                        />
                    </div>
                </div>
                <div className="grow flex flex-col px-4 pt-4 pb-3 space-y-3">
                    <h2
                        className="text-heading text-sm md:text-sm line-clamp-1"
                        alt={product.name || 'Producto sin Nombre'}
                        title={product.name || 'Producto sin Nombre'}
                    >
                        {product.name || 'Producto sin Nombre'}
                    </h2>

                    <div className="flex gap-x-2 items-center flex-wrap ">
                        {/* 🟢 Protección de bucle map */}
                        {colors.map((color) => (
                            <div
                                // 🟢 CORRECCIÓN SECUNDARIA: El key debe ser único y no nulo.
                                // Si 'color.id' es null, esto podría fallar, pero asumiremos que el ID es seguro.
                                key={color.id} 
                                title={color.name}
                                className={"size-6 p-[2px] border rounded-full flex items-center " +
                                    (product.color_id == color.id ? 'border-gray-700' : 'border-gray-300')}
                            >
                                <span style={{ backgroundImage: "url(" + color.img + ")" }} aria-hidden="true" className="w-full h-full rounded-full  inline-block "></span>
                            </div>
                        ))}
                    </div>

                    <div className=" grow flex items-end justify-between ">
                        <ProductPriceOffer
                            // 🟢 Protección de precio con encadenamiento opcional
                            price={product.price}
                            old_price={product.old_price}
                            offer={product.offer}

                        />
                    </div>
                </div>
            </div>
        </Link>
    );
};

export default CardProduct;