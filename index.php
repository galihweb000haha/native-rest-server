<?php
function requestAPI($url) {
    // Initiate curl session in a variable (resource)
    $curl_handle = curl_init();    
    
    // Set the curl URL option
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    
    // This option will return data as a string instead of direct output
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    
    // Execute curl & store data in a variable
    $curl_data = curl_exec($curl_handle);
    
    curl_close($curl_handle);
    
    // Decode JSON into PHP array
    $response_data = json_decode($curl_data);
    
    // Print all data if needed
    // print_r($response_data);
    // die();
    
    // All user data exists in 'data' object
     return $response_data->records;
    
    // Extract only first 5 user data (or 5 array elements)
    // $user_data = array_slice($user_data, 0, 4);
    
    // Traverse array and print employee data
}

if(array_key_exists('delete', $_POST)) { 
    delete($_POST['id']); 
} 
else if(array_key_exists('update', $_POST)) { 
    button2(); 
} 
function delete($id) { 
    $data = array(
        'id' => $id,    
    );
     
    // echo json_encode($data);

    // (A) INIT CURL
    $ch = curl_init();

    // (B) CURL OPTIONS
    curl_setopt($ch, CURLOPT_URL, "http://localhost/api/product/delete.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // (C) CURL FETCH
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
    // (C1) CURL FETCH ERROR
    echo curl_error($ch);
    } else {
    // (C2) CURL FETCH OK
    $info = curl_getinfo($ch);
    echo $result; // Whatever Wikipedia returns
    print_r($info); // More information on the transfer
    }

    // (D) CLOSE CONNECTION
    curl_close($ch);

} 
function button2() { 
    echo "This is Button2 that is selected"; 
} 

$url = "http://localhost/api/product/read.php";
$products = requestAPI($url);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Products</h2>
    <a href="#">Insert data</a>
    <?php foreach ( $products as $p ) : ?>
        <ul>
            <li>Nama : <?= $p->name ?></li>
            <li>Description : <?= $p->description ?></li>
            <li>Price : <?= '$'.$p->price ?></li>
            <li>Category : <?= $p->category_name ?></li>
            <li>
                <form method="post"> 
                    <input type="submit" name="delete"
                            class="button" value="Delete" /> 
                    <input type="hidden" name="id" value="<?= $p->id ?>">
                    <input type="submit" name="update"
                            class="button" value="Update" /> 
                </form>
            </li>
        </ul>
    <?php endforeach; ?>
</body>
</html>