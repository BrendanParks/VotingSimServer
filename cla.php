<?php

class CLA {

  // singleton instance
  private static $userid, $validationNumber;

  // private constructor function
  // to prevent external instantiation
  private __construct() {  }

  // getInstance method
  public static function getId() {

    return self::$userid;

  }

  public static function setId($token) {

    if(validToken($token))
      {
        $id = id_by_token($token);
        self::$userid = $id;
        return $userid;
        
        
      }

  }

  function validValidationNumber($number)
{

  //compare number to news in database
  $random_number_query = mysql_query("SELECT `valid_num` FROM `cla_valid_nums` WHERE `valid_num`='".$number."'");
  $random_number_rows = mysql_num_rows($random_number_query);
  if(is_int($number) && $random_number_rows<1)
    {
      //if not found then number is valid
      return true;
      
    }else
    {
      //if found then number is not valid
      return false;
      

    }
 

}

  public static function newValidationNumber()
  {
    
    //select new random number
    $random_number = rand(0, 1000000000); //Afraid of integer overflow in SQL...
    
    //call validRandomNumber
    if(!validValidationNumber($random_number)) {
        newValidationNumber();
    }
    
    self::$validationNumber = $random_number;
    return $random_number;

    //if true then save number

  }
  public static function add_to_vnum_request_list(){
    mysql_query("INSERT INTO `voting_bp`.`cla_valid_nums` (`user_id`, `valid_num`) VALUES ('".self::$userid."', '".self::newValidationNumber()."');");
    return self::$validationNumber;

  }

} 


?>