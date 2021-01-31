<?php
class Article
{
  
    // database connection and table name
    private $conn;
    private $table_name = "artikel";
  
    // object properties
    public $id_artikel;
    public $judul_artikel;
    public $waktu_artikel;
    public $isi_artikel;
    public $kategori;
    public $nama_creator;
    public $gambar_artikel;
    public $hits;
    public $id_kategori;
    public $id_user;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
    
        // select all query
        $query = "SELECT
                    artikel.id_artikel,
                    artikel.judul_artikel,
                    artikel.waktu_artikel,
                    artikel.isi_artikel,
                    kategori.kategori AS kategori,
                    user.nama_pengguna AS nama_creator,
                    artikel.gambar_artikel,
                    artikel.hits
                FROM artikel
                LEFT JOIN kategori ON kategori.id_kategori = artikel.id_kategori
                LEFT JOIN user ON user.id_user = artikel.id_user";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // create product
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    judul_artikel=:judul_artikel,
                    waktu_artikel=:waktu_artikel,
                    id_kategori=:id_kategori,
                    id_user=:id_user,
                    isi_artikel=:isi_artikel,
                    gambar_artikel=:gambar_artikel,
                    hits=:hits";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->judul_artikel=htmlspecialchars(strip_tags($this->judul_artikel));
        $this->waktu_artikel=htmlspecialchars(strip_tags($this->waktu_artikel));
        $this->id_kategori=htmlspecialchars(strip_tags($this->id_kategori));
        $this->id_user=htmlspecialchars(strip_tags($this->id_user));        
        $this->isi_artikel=htmlspecialchars(strip_tags($this->isi_artikel));
        $this->gambar_artikel=htmlspecialchars(strip_tags($this->gambar_artikel));
        $this->hits=htmlspecialchars(strip_tags($this->hits));

        // bind values
        $stmt->bindParam(":judul_artikel", $this->judul_artikel);
        $stmt->bindParam(":waktu_artikel", $this->waktu_artikel);
        $stmt->bindParam(":id_kategori", $this->id_kategori);
        $stmt->bindParam(":id_user", $this->id_user);        
        $stmt->bindParam(":isi_artikel", $this->isi_artikel);
        $stmt->bindParam(":gambar_artikel", $this->gambar_artikel);
        $stmt->bindParam(":hits", $this->hits);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }
    // used when filling up the update product form
    function readOne(){
    
        // query to read single record
        $query = "SELECT
                    artikel.id_artikel,
                    artikel.judul_artikel,
                    artikel.waktu_artikel,
                    artikel.isi_artikel,
                    kategori.kategori AS kategori,
                    user.nama_pengguna AS nama_creator,
                    artikel.gambar_artikel,
                    artikel.hits
                FROM artikel
                LEFT JOIN kategori ON kategori.id_kategori = artikel.id_kategori
                LEFT JOIN user ON user.id_user = artikel.id_user
                WHERE id_artikel = ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_artikel);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_artikel = $row['id_artikel'];
        $this->judul_artikel = $row['judul_artikel'];
        $this->waktu_artikel = $row['waktu_artikel'];
        $this->kategori = $row['kategori'];
        $this->nama_creator = $row['nama_creator'];
        $this->isi_artikel = $row['isi_artikel'];
        $this->gambar_artikel = $row['gambar_artikel'];
        $this->hits = $row['hits'];
    }
    // update the product
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    price = :price,
                    description = :description,
                    category_id = :category_id
                WHERE
                    id = :id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // delete the product
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_artikel = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id_artikel=htmlspecialchars(strip_tags($this->id_artikel));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_artikel);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // search products
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
                ORDER BY
                    p.created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // read products with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                ORDER BY p.created DESC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }
    // used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }
}