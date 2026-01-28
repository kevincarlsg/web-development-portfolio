import Banner from "@/Components/Carousel/Banner";
import CardProduct from "@/Components/Cards/CardProduct";
import CarouselBanner from "@/Components/Carousel/CarouselBanner";
import SectionList from "@/Components/Sections/SectionList";

import Layout from "@/Layouts/Layout";
import { Head, usePage } from "@inertiajs/react";
import CarouselTop from "./CarouselTop";
import GridProduct from "@/Components/Grids/GridProduct";
import CarouselSection from "./CarouselSection";
import MetaTag from "@/Components/MetaTag";

export default function Home({
    page,
    // 🟢 MEJORA DE ROBUSTEZ: Inicializamos arrays a [] para prevenir fallos si vienen como null
    brands = [],
    carouselTop = [],
    bannersTop = [],
    productsBestSeller = [],
    bannersMedium = [],
    newProducts = [],
    bannersBottom = [],
    categoriesProductCount = [],
}) {
    // console.log(productsBestSeller[0]);
    return (
        <>
            <MetaTag metaTag={page.metaTag} />

            <Layout>
                <div className="container">
                    {/* El resto del código usa .map() y .length>0 de forma segura */}
                    <div className="py-content grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-8 ">
                        <div className="col-span-1 md:col-span-2 ">
                            <CarouselTop images={carouselTop} />
                        </div>

                        {/* El bucle ahora es seguro aunque bannersTop venga como null */}
                        {bannersTop.map((item, index) => (
                            <div key={item?.id ?? index}>
                                <a href={item.link} target="blank">
                                    <img className="h-full mx-auto object-cover w-full rounded-lg overflow-hidden"
                                        src={item.img}
                                        alt={item.alt}

                                    />
                                </a>
                            </div>
                        ))}
                    </div>

                    <SectionList title={"Categorias"}>
                        <CarouselSection
                            items={categoriesProductCount}
                            searchType="categories[]"
                        />
                    </SectionList>

                    {/* La comprobación .length > 0 ahora es segura */}
                    {bannersMedium.length > 0 && (
                        <div className="py-content ">
                            <Banner image={bannersMedium[0]} />
                        </div>
                    )}

                    {productsBestSeller.length > 0 && (
                        <SectionList title="Los mas vendidos">
                            <GridProduct>
                                {productsBestSeller.map((product, index) => (
                                    <CardProduct 
                                        key={product?.id ?? index} 
                                        product={product}
                                    />
                                ))}
                            </GridProduct>
                        </SectionList>
                    )}

                    <SectionList title={"Los recién llegados"}>
                        <div className="py-2 relative">
                            <GridProduct>
                                {newProducts.map((product, index) => (
                                    <CardProduct 
                                        key={product?.id ?? index} 
                                        product={product} 
                                        productNew={true} 
                                    />
                                ))}
                            </GridProduct>
                        </div>
                    </SectionList>

                    {bannersBottom.length > 0 && (
                        <div className="py-content">
                            <CarouselBanner images={bannersBottom} />
                        </div>
                    )}
                    {brands.length > 1 && (
                        <SectionList title={"Top Marcas"}>
                            <CarouselSection items={brands} searchType="brands" />
                        </SectionList>
                    )}
                </div>
            </Layout>
        </>
    );
}