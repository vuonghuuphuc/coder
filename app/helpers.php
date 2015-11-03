<?php

function getIpInfo($ip)
{
    try{
        $array = (array)json_decode(file_get_contents("http://ip-api.com/json/" . $ip),true);
        if(isset($array['status'])){
            if($array['status'] == 'success'){
                return $array;
            }
        }
        return null;
    }catch(Exception $error){
        return null;
    }
}
