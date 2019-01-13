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
     * Address constructor.
     * @param int|null $postalCode
     * @param string|null $city
     * @param string|null $street
     * @param string|null $country
     */
    public function __construct($postalCode, $city, $street, $country)
    {
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->street = $street;
        $this->country = $country;
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
            // we make sure $id is an integer
            $id = intval($bikeId);
            $req = $db->prepare('SELECT address.postalCode, address.city, address.street, address.country
                                          FROM bike
                                          JOIN address ON bike.addressId = address.id
                                          WHERE bike.id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => (int)$bikeId));
            $address = $req->fetch();
            
            return new Address($address['postalCode'], $address['city'], $address['street'], $address['country']);
            
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: " . $bikeId);
        }
    }
}