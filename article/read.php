<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/article.php';
  
// instantiate database and article object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$article = new Article($db);
  
// query articles
$stmt = $article->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // articles array
    $articles_arr=array();
    $articles_arr["records"]=array();   

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $article_item=array(
            "id_artikel"        => $id_artikel,
            "judul_artikel"     => $judul_artikel,
            "waktu_artikel"     => $waktu_artikel,
            "isi_artikel"       => html_entity_decode($isi_artikel),
            "kategori"          => $kategori,
            "nama_creator"      => $nama_creator,
            "gambar_artikel"    => $gambar_artikel,
            "hits"              => $hits
        );
  
        array_push($articles_arr["records"], $article_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show articles data in json format
    echo json_encode($articles_arr);
}
  
// no articles found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no articles found
    echo json_encode(
        array("message" => "No articles found.")
    );
}