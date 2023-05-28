<?php

/*
fashion.beautyweb@gmail.com
@ttilio33

https://drop.novaengel.com/

CSV Fields:
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

use Mc\Novaengel\Script;

require __DIR__ . '/vendor/autoload.php';

define( 'SCRIPT_DIR', __DIR__ );

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiAuthData = [
    'user' => $_ENV['NE_USER'],
    'pass' => $_ENV['NE_PASS'],
    'url'  => $_ENV['NE_URL'],
];

print_r($apiAuthData);

Script::run($apiAuthData);
