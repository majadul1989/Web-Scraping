<?php

include('config.php');

for($i = 1; $i <= 14; $i++ ){


    $html = file_get_contents('https://www.bdtradeinfo.com/yellowpages/des_data.php?page='. $i .'&subcategory_id=492'); //get the html returned from the following url

    $pokemon_doc = new DOMDocument();

    libxml_use_internal_errors(TRUE); //disable libxml errors

    if(!empty($html)){ //if any html is actually returned

        $pokemon_doc->loadHTML($html);
        libxml_clear_errors(); //remove errors for yucky html
        
        $pokemon_xpath = new DOMXPath($pokemon_doc);
       
        // $datas = $pokemon_xpath->query('//a[@class="nav12txt"]/text()');
        $classname = 'nav12txt';
        $medicale_name = $pokemon_xpath->query("//h4/a[@class='nav12txt']");
        $medicale_address = $pokemon_xpath->query("//ul/li/text()");
        $medicale_web = $pokemon_xpath->query("//ul/li/a/text()");

        foreach($medicale_name as $k => $row){
            if(!empty($medicale_web[$k])){
                $hospitalWeb = trim(preg_replace('/\s+/', ' ', $medicale_web[$k]->nodeValue));
            } else {
                $hospitalWeb = "---"; 
            }
           $hospitalName = trim(preg_replace('/\s+/', ' ', $row->nodeValue));
           $hospitalAddress = trim(preg_replace('/\s+/', ' ', $medicale_address[$k]->nodeValue));

           $sql = "INSERT INTO `hospital` (`hospital_name`, `hospital_address`, `web`, `hospital_logo`, `hospital_pad_design`, `hospital_status`, `description`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES ( '$hospitalName', '$hospitalAddress', '$hospitalWeb', '---', 'default', 'Active', '---', 1, 1, NOW(), NOW())";

            $conn->query($sql); 


        }
    }       
}
?>