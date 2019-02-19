<?php

class Address
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var int|null
     */
    public $postalCode;
    
    /**
     * @var string|null
     */
    public $city;
    
    /**
     * @var string|null
     */
    public $street;
    
    /**
     * @var string|null
     */
    public $country;
    
    /**
     * @var int|null
     */
    public $lat;
    
    /**
     * @var int|null
     */
    public $lon;
    
    /**
     * Address constructor.
     * @param int|null $postalCode
     * @param string|null $city
     * @param string|null $street
     * @param string|null $country
     * @param int|null $lat
     * @param int|null $lon
     * @param int|null $id
     */
    public function __construct($postalCode = null, $city = null, $street = null, $country = null, $lat = null, $lon = null, $id = null)
    {
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->street = $street;
        $this->country = $country;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->id = $id;
    }
    
    /**
     * @param int $bikeId
     * @return Address
     * @throws Exception
     */
    public static function getBikeAddress($bikeId)
    {
        try {
            $db = Db::getInstance();

            $req = $db->prepare('SELECT address.postalCode, address.city, address.street, address.country, address.id
                                          FROM bike
                                          JOIN address ON bike.addressId = address.id
                                          WHERE bike.id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $bikeId));
            $address = $req->fetch();
            
            return new Address($address['postalCode'], $address['city'], $address['street'], $address['country'], null, null, $address['id']);
            
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: " . $bikeId);
        }
    }
    
    /**
     * @param Address $address
     * @return int
     * @throws Exception
     */
    public static function register($address)
    {
        try {
            $db = Db::getInstance();
            
            $req = $db->prepare('INSERT INTO address(postalCode, city, street, latitude, longitude, country)
                                          VALUES(:postalCode, :city, :street, :latitude, :longitude, :country)');
            $req->execute(array(
                'postalCode' => $address->postalCode,
                'city' => $address->city,
                'street' => $address->street,
                'country' => $address->country,
                'latitude' => $address->lat,
                'longitude' => $address->lon
            ));
            
            $req = $db->prepare('SELECT LAST_INSERT_ID() FROM address');
            $req->execute();
            return $req->fetch()[0];
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new address");
        }
    }
    
    /**
     * @param Address $address
     * @param int $addressId
     * @return int
     * @throws Exception
     */
    public static function update($address, $addressId)
    {
        try {
            $db = Db::getInstance();
    
            $req = $db->prepare("UPDATE address
                                          SET postalCode = :postalCode, city = :city, street = :street, latitude = :lat, longitude = :lon, country = :country
                                          WHERE id = :id");
            $req->bindParam(':postalCode', $address->postalCode);
            $req->bindParam(':city', $address->city);
            $req->bindParam(':street', $address->street);
            $req->bindParam(':lat', $address->lat);
            $req->bindParam(':lon', $address->lon);
            $req->bindParam(':country', $address->country);
            $req->bindParam(':id', $addressId);
            $req->execute();
            
        } catch (Exception $e){
            throw new Exception("DB error when updating address with id " . $addressId);
        }
    }
}