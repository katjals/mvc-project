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
    public $score;
    
    /**
     * @var Address
     */
    public $address;
    
    /**
     * Bike constructor.
     * @param string $title
     * @param string $description
     * @param int $price
     * @param int|null $id
     * @param int|null $score
     * @param Address|null $address
     */
    public function __construct($title, $description, $price, $id = null, $score = null, $address = null){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->address = $address;
        $this->score = $score;
    }
    
    /**
     * @param string $startDate
     * @param string $endDate
     * @param int $lat
     * @param int $lon
     * @param int $radius
     * @return array
     * @throws Exception
     */
    public static function getAllNonBooked($startDate, $endDate, $lat, $lon, $radius)
    {
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT bike.id, bike.title, bike.description, bike.price, address.latitude, address.longitude
                                          FROM bike
                                          JOIN address ON bike.addressId = address.id
                                          WHERE bike.id NOT IN
                                          (SELECT booking.bikeId FROM booking
                                          WHERE (:startDate and :endDate BETWEEN booking.startTime AND booking.endTime)
                                          OR (:startDate BETWEEN booking.startTime AND booking.endTime)
                                          OR (:endDate BETWEEN booking.startTime AND booking.endTime)
                                          OR (booking.startTime >= :startDate AND booking.endTime <= :endDate)
                                          )
                                          AND (
                                          ACOS( SIN( RADIANS( address.latitude ) ) * SIN( RADIANS( :lat ) ) + COS( RADIANS( address.latitude ) )
                                            * COS( RADIANS( :lat )) * COS( RADIANS( address.longitude ) - RADIANS( :lon )) ) * 6380 < :radius
                                            )
                                          ');
            $req->execute(array(
                'startDate' => (new DateTime($startDate))->format('Y-m-d H:i'),
                'endDate' => (new DateTime($endDate))->format('Y-m-d H:i'),
                'lat' => $lat,
                'lon' => $lon,
                'radius' => $radius));
            $results = $req->fetchAll();
    
            $bikes = [];
            foreach($results as $row){
                $bike = new Bike($row['title'], $row['description'], $row['price'], $row['id'],4.3,
                    new Address(null, null, null, null, $row['latitude'], $row['longitude']));
                //TODO use setter
                
                $bikes[] = $bike;
            }

            return $bikes;
    
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

            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $bikeId));
            $bike = $req->fetch();
    
            return new Bike($bike['title'], $bike['description'], $bike['price'], $bike['id'],4.3);
    
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: ".$bikeId);
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
            $req = $db->prepare('SELECT *
                                          FROM bike
                                          WHERE userId = :userId');
            $req->execute(array('userId' => $_SESSION['id']));
            
            // we create a list of Bike objects from the db result
            foreach($req->fetchAll() as $bike){
                $bikes[$bike['id']] = new Bike($bike['title'], $bike['description'], $bike['price'], $bike['id']);
            }
        
            return $bikes;
        
        } catch (Exception $e){
            throw new Exception("DB error when fetching users own bikes");
        }
    }
    
    /**
     * @param Bike $bike
     * @throws Exception
     */
    public static function register($bike)
    {
        try {
            $db = Db::getInstance();
    
            $req = $db->prepare('INSERT INTO bike(title, description, price, addressId, userId)
                                          VALUES(:title, :description, :price, :addressId, :userId)');
            $req->execute(array(
                'title' => $bike->title,
                'description' => $bike->description,
                'price' => $bike->price,
                'addressId' => $bike->address->id,
                'userId' => $_SESSION['id']
            ));
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
    
    /**
     * @param Bike $bike
     * @return int $addressId
     * @throws Exception
     */
    public static function update($bike)
    {
        try {
            $db = Db::getInstance();
            
            $req = $db->prepare("UPDATE bike
                                          SET title = :title, description = :description, price = :price
                                          WHERE id = :id");
            $req->bindParam(':title', $bike->title);
            $req->bindParam(':description', $bike->description);
            $req->bindParam(':price', $bike->price);
            $req->bindParam(':id', $bike->id);
            $req->execute();
    
            $req = $db->prepare("SELECT addressId
                                          FROM bike
                                          WHERE id = :id");
            $req->bindParam(':id', $bike->id);
            $req->execute();
            return $req->fetch()[0];
    
        } catch (Exception $e){
            throw new Exception("DB error when editing bike with id " . $bike->id);
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
            $req = $db->prepare('SELECT *
                                          FROM bike
                                          WHERE id = :id');
            $req->execute(array('id' => $id));
            $bike = $req->fetch();
            
            return $bike['userId'];

            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
}