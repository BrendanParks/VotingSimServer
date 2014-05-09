<?php

class CTF {

  // private constructor function
  // to prevent external instantiation
  private __construct() {  }

  // getInstance method
  public static function can_vote($v_num) {

    mysql_query("");//query to check if v_num is in table

  }

  public static function record_vote($user_id, $c_id){

      mysql_query("");//insert vote to table
      return true;
  }

  public static function publishResults(){
    //
  }

  

} 


?>