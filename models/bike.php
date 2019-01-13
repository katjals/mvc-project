<?php

class Bike {
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string
     */
    public $title;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var int
     */
    public $price;
    
    /**
     * @var int
     */
    public $postalCode;
    
    /**
     * Bike constructor.
     * @param string $title
     * @param string $description
     * @param int $price
     * @param int $postalCode
     * @param int|null $id
     */
    public function __construct($title, $description, $price, $postalCode, $id = null){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->postalCode = $postalCode;
    }
    
    /**
     * @param string $startDate
     * @param string $endDate
     * @return string
     * @throws Exception
     */
    public static function getAllNonBooked($startDate, $endDate)
    {
        try {
            // Start XML file, create parent node
            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->xmlVersion = '1.0';
            $dom->formatOutput = true;
            $xml_file_name = 'markers.xml';
            
            $root = $dom->createElement("markers");
            
            $db = Db::getInstance();
            $req = $db->prepare('SELECT bike.id, bike.title, bike.description, bike.price, address.latitude, address.longitude FROM bike
                                          JOIN address ON bike.addressId = address.id
                                          WHERE bike.id NOT IN
                                          (SELECT booking.bikeId FROM booking
                                          WHERE :startDate and :endDate BETWEEN booking.startTime AND booking.endTime)
                                          ');
            $req->execute(array(
                'startDate' => (new DateTime($startDate))->format('Y-m-d H:i'),
                'endDate' => (new DateTime($endDate))->format('Y-m-d H:i')));
            $results = $req->fetchAll();
    
    
            // Iterate through the rows, adding XML nodes for each
            foreach($results as $row){
                // Add to XML document node
                $marker_node = $dom->createElement("marker");
        
                $attr_marker_id = new DOMAttr('marker_id', $row['id']);
                $marker_node->setAttributeNode($attr_marker_id);
        
                $child_node_id = new DOMAttr('id', $row['id']);
                $marker_node->setAttributeNode($child_node_id);
        
                $child_node_title = new DOMAttr('title', $row['title']);
                $marker_node->setAttributeNode($child_node_title);
        
                $child_node_desc = new DOMAttr('description', $row['description']);
                $marker_node->setAttributeNode($child_node_desc);
        
                $child_node_price = new DOMAttr('price', $row['price']);
                $marker_node->setAttributeNode($child_node_price);
    
                $child_node_lat = new DOMAttr('lat', $row['latitude']);
                $marker_node->setAttributeNode($child_node_lat);
    
                $child_node_lng = new DOMAttr('lng', $row['longitude']);
                $marker_node->setAttributeNode($child_node_lng);
        
                $root->appendChild($marker_node);
            }
    
            $dom->appendChild($root);
            $dom->save($xml_file_name);

            return $dom->saveXML();
    
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bikes");
        }
    }
    
    /**
     * @param int $bikeId
     * @return Bike
     * @throws Exception
     */
    public static function getOne($bikeId){
        
        try {
            $db = Db::getInstance();
            // we make sure $id is an integer
            $id = intval($bikeId);
            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $bikeId));
            $bike = $req->fetch();
    
            return new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
    
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: ".$id);
        }
    }
    
    /**
     * @return Bike[]
     * @throws Exception
     */
    public static function getOwnBikes()
    {
        $bikes = [];
    
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT * FROM bike WHERE userId = :userId');
            $req->execute(array('userId' => $_SESSION['id']));
            
            // we create a list of Bike objects from the db result
            foreach($req->fetchAll() as $bike){
                $bikes[] = new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
            }
        
            return $bikes;
        
        } catch (Exception $e){
            throw new Exception("DB error when fetching users own bikes");
        }
    }
    
    /**
     * @param $title
     * @param $description
     * @param $price
     * @param $streetNumber
     * @param $streetName
     * @param $city
     * @param $postalCode
     * @param $country
     * @param $lat
     * @param $lon
     * @return bool
     * @throws Exception
     */
    public static function register($title, $description, $price, $streetNumber,
                                    $streetName, $city, $postalCode, $country, $lat, $lon)
    {
        try {
            $db = Db::getInstance();
    
            $req = $db->prepare('INSERT INTO address(postalCode, city, street, latitude, longitude, country)
                                      VALUES(:postalCode, :city, :street, :latitude, :longitude, :country)');
            $req->execute(array(
                'postalCode' => $postalCode,
                'city' => $city,
                'street' => $streetName . " " . $streetNumber,
                'country' => $country,
                'latitude' => $lat,
                'longitude' => $lon,
            ));
    
            $req = $db->prepare('SELECT LAST_INSERT_ID() FROM address');
            $req->execute();
            $addressId = $req->fetch()[0];
    
            $req = $db->prepare('INSERT INTO bike(title, description, price, addressId, userId)
                                      VALUES(:title, :description, :price, :addressId, :userId)');
            $req->execute(array(
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'addressId' => $addressId,
                'userId' => $_SESSION['id']
            ));
    
            return true;
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
    
    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $price
     * @param $postalCode
     * @return bool
     * @throws Exception
     */
    public static function update($id, $title, $description, $price, $postalCode)
    {
        try {
            $db = Db::getInstance();
    
            //TODO: set values outside query
            $req = $db->prepare("UPDATE bike SET title = '$title', description = '$description', price = '$price',
                                    postalCode = '$postalCode' WHERE id = '$id'");
            $req->execute();
    
            return true;
    
        } catch (Exception $e){
            throw new Exception("DB error when editing bike with id " . $id);
        }
    }
    
    /**
     * //todo move to booking model
     * @param int $bikeId
     * @param DateTime $endDate
     * @param DateTime $startDate
     * @return bool
     * @throws Exception
     */
    public static function book($bikeId, $endDate, $startDate)
    {
        try {
            $db = Db::getInstance();
            $req = $db->prepare('INSERT INTO booking(startTime, endTime, userId, bikeId)
                                      VALUES(:startTime, :endTime, :userId, :bikeId)');
            $req->execute(array(
                'startTime' => (new DateTime($startDate))->format('Y-m-d H:i'),
                'endTime' => (new DateTime($endDate))->format('Y-m-d H:i'),
                'userId' => $_SESSION['id'],
                'bikeId' => $bikeId
            ));
            
            return true;
    
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
    
    /**
     * @param int $bikeId
     * @return int
     * @throws Exception
     */
    public static function getOwnerId($bikeId){
        
        try {
            $db = Db::getInstance();
            $id = intval($bikeId);
            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            $req->execute(array('id' => $id));
            $bike = $req->fetch();
            
            return $bike['userId'];

            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
}