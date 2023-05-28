<?php

/*
 * CSV Fields:
"Brand",
"Brand Nr.",
"Reference",
"EAN",
"Description",
"RRP",
"Retail Price",
"Stock",
"Categories",
"Gender",
"Id",
"Image",
"Brand new",
"Kgs",
"Vat",
"Width",
"Height",
"Depth",
"Date",
"Contents",
"Range",
"Properties",
"Tags",
"Set Content",
"End Offer",
"Offer",
"Familia Completa",
"Manufacturing Country",
"Ingredients",
"NombreColor",
"ID Linea",
"Nombre Linea"

    [4088] => Array
        (
            [Id] => 153469
            [EANs] => Array
                (
                    [0] => 7618900910416
                )

            [Description] => NAIL Tratamiento minute quick finish 5 ml
            [SetContent] =>
            [Price] => 3.7
            [PVR] => 7.75
            [Stock] => 3
            [BrandId] => 1198
            [BrandName] => MAVALA
            [LineaId] => 10416
            [LineaName] => NAIL TRATAMIENTO
            [Gender] => Woman
            [Families] => Array
                (
                    [0] => Make-Up
                )

            [CompleteFamilies] => Array
                (
                    [0] => Make-Up/Unghie
                )

            [Kgs] => 0.027
            [Ancho] => 20
            [Alto] => 55
            [Fondo] => 20
            [IVA] => 21
            [Fecha] => 2021-03-10
            [Contenido] => 5 ml
            [Gama] => 10416
            [ItemId] => 1198-10416
            [Properties] => Array
                (
                )

            [Tags] => Array
                (
                )

            [EsOferta] =>
            [FechaFinalOferta] =>
            [Novedad] => False
            [PaisFabricacion] => CH
            [Ingredientes] =>
            [NombreColor] =>
        )
 */

namespace Mc\Novaengel;

class Script {
    public static function run($authData) {
        $api = new Api($authData['url'], $authData['user'], $authData['pass']);

        echo "LOGIN RESPONSE: ";
        $api->login();

        $data = [];

        $downloadLimit = $_ENV['DOWNLOAD_LIMIT'] ?? -1;

        $doDownloadImages = $_ENV['DONWLOAD_IMAGES'] === 'true' ? true : false;
        $imagesDownloadDir = $_ENV['IMAGES_DIR'];

        if($doDownloadImages === true) {

            if (file_exists($imagesDownloadDir) && is_dir($imagesDownloadDir))
            {
                echo "Directory $imagesDownloadDir already exists";
            }else{
                echo "Directory $imagesDownloadDir Not exists. Creating...";
                mkdir($imagesDownloadDir, 0755, true);
            }
        }

        $productCount = 0;
        $imageStreamContext = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        foreach($api->productsList() as $productNumber => $product) {
            if($productCount >= $downloadLimit) {
                break;
            }

            $imageUrl = $api->productImage( $product['Id'] );

            $imageFilename = basename(
                parse_url( $imageUrl )['path']
            );

            if($doDownloadImages === true) {
                echo "\nDownloading#$productCount: $imageUrl\n";

                copy(
                    $imageUrl,
                    $imagesDownloadDir . '/' . $imageFilename,
                    stream_context_create( $imageStreamContext )
                );
                /*$image = file_get_contents( $imageUrl, false, stream_context_create( $imageStreamContext ));*/
            }

            $product['ID'] = '133';
            $product['productID'] = $product['Id'];
            $product['Image'] = str_replace(
                $_ENV['NE_IMAGE_BASE_URL'],
                $_ENV['IMAGE_BASE_URL'],
                $imageUrl
            );
            $product['EAN'] = $product['EANs'][0];
            $product['Category'] = $product['Families'][0];

            $prodCatParts = explode("/", $product['CompleteFamilies'][0]);

            // echo "PROD_CAT_PARTS: \n";
            // print_r($prodCatParts);

            $product['Category1'] = $prodCatParts[0];
            $product['Category2'] = $prodCatParts[1];
            $product['Category3'] = $product['Gender'];

            unset($product['EANs']);
            unset($product['Families']);
            unset($product['CompleteFamilies']);
            // unset($product['Gender']);

            $data[] = $product;

            echo "Donwloading Product ${product['Id']}\n";

            // echo "PRODUCT: ";
            // print_r($product);

            // echo json_encode($product) . "\n\n";

            $productCount++;
            //echo $product['Id'] . ',"' . $api->productImage( $product['Id'] ) . "\n";
        }

        // echo json_encode($data);

        $outputFilename = $_ENV['OUTPUT_FILENAME'];

        file_put_contents(
            SCRIPT_DIR . '/output/' . $outputFilename,
            json_encode($data)
        );

        echo "Generated " . SCRIPT_DIR . '/output/' . $outputFilename;
    }
}