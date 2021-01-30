<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate article object
include_once '../objects/article.php';
  
$database = new Database();
$db = $database->getConnection();
  
$article = new Article($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
var_dump($data);
  
// make sure data is not empty
if(
    !empty($data->judul_artikel) &&
    !empty($data->isi_artikel) &&
    !empty($data->id_kategori) &&
    !empty($data->id_user)
){
  
    // set article property values
    $article->judul_artikel = $data->judul_artikel;
    $article->waktu_artikel = date('Y-m-d H:i:s');
    $article->id_kategori = $data->id_kategori;
    $article->id_user = $data->id_user;
    $article->isi_artikel = $data->isi_artikel;
    $article->gambar_artikel = $data->gambar_artikel;
    $article->hits = $data->hits;
  
    // create the article
    if($article->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "article was created."));
    }
  
    // if unable to create the article, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create article."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create article. Data is incomplete."));
}
?>