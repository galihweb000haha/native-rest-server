<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/article.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare article object
$article = new Article($db);
  
// set ID property of record to read
$article->id_artikel = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of article to be edited
$article->readOne();
  
if($article->id_artikel!=null){
    // create array
    $article_arr = array(
        "id_artikel"        => $article->id_artikel,
        "judul_artikel"     => $article->judul_artikel,
        "waktu_artikel"     => $article->waktu_artikel,
        "kategori"       => $article->kategori,
        "nama_creator"           => $article->nama_creator,
        "isi_artikel"       => $article->isi_artikel,
        "gambar_artikel"    => $article->gambar_artikel,
        "hits"              => $article->hits
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($article_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user article does not exist
    echo json_encode(array("message" => "article does not exist."));
}
?>