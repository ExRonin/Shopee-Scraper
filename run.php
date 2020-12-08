<?php
error_reporting(0);

unlink('hasil/json/results.json');
require __DIR__ . '/vendor/autoload.php';
include "modules/function.php";

use \Curl\Curl;

$curl = new Curl();
$banner = "
Shopee Scraper By Praaaaa Coder
";
print $banner;
echo "\nCari Apa ? ";
$search = trim(fgets(STDIN));

echo "Berapa Barang ? ";
$totalSearch = trim(fgets(STDIN));

$getSearch = getSearch($curl, $search, $totalSearch);
if($getSearch->error == null) {
    $no = 0;
    for ($x = 0; $x < $totalSearch; $x++) {
        $no++;
        $itemID = $getSearch->items[$x]->itemid;
        $shopID = $getSearch->items[$x]->shopid;

        $getItem = getItem($curl, $itemID, $shopID);
            $nameItem = $getItem->item->name;
            $priceItem = $getItem->item->price;
            $statusItem = $getItem->item->item_status;
            $lokasiToko = $getItem->item->shop_location;
            $deskripsi = $getItem->item->description;
            $imageItem = 'https://cf.shopee.co.id/file/' . $getItem->item->image;
            $gambarlengkap = $getItem->item->images;
            $arraygambar = count($gambarlengkap);
            $gratisongkir = $getItem->item->show_free_shipping;
            $sold = $getItem->item->historical_sold;
            

            if($statusItem == "normal") {
                $status = "Tersedia";
            } else {
                $status = "Tidak Tersedia";
            }

            if($gratisongkir == true) {
                $statusongkir = "Free Shiping/Gratis Ongkir";
            } else {
                $statusongkir = "Tidak Free Shiping/Gratis Ongkir";
            }

            echo $no . '. Status barang = ' . $status . ', Sold = ' . $sold. ', Ongkir = ' . $statusongkir. ', Jumlah gambar = ' . $arraygambar. ', Harga Barang = ' . $priceItem . ',  Nama Barang = ' . $nameItem . ', Lokasi toko = ' . $lokasiToko . ', File = ' . $imageItem . " \n";
            

            $export['data'][] = array(
                    'no' => $no,
                    'status' => $status,
                    'nama' => $nameItem,
                    'harga' => $priceItem,
                    'lokasi' => $lokasiToko,
                    'foto' => $imageItem,
                    'deskripsi' => $deskripsi,
                    'status' => $status
                );
        
            if (($id = fopen('hasil/json/results.json', 'wb'))) {
                fwrite($id, json_encode($export));
                fclose($id);
            }
        }
    } 
  
  


    ob_start();
    htmlConverter();
    $htmlResults = ob_get_contents();
    ob_end_clean(); 
    file_put_contents("hasil/html/results.html", $htmlResults);
    
    echo "\n\e[0;32mSuccessfully scrape data from Shopee #RoninCoder.\e[0m\n\n";
    echo "\e[0;31mFile saved :\n";
    echo "JSON : hasil/json/results.json\n";
    echo "HTML : hasil/html/results.html\e[0m";
    
/**
 */
?>