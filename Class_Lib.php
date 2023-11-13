<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Class_Lib
 *
 * @author sandr
 */
class Class_Lib {

}

class Book {
    public $id;
    public $title;
    public $price;     
}

class BookFile extends SplFileObject {
   
    public function __construct($filePath)
    {
           parent::__construct($filePath);
    }
    
    
    public function getBooks()
    {  
        $newArray = array();
        foreach ($this as $book) //looping through txt file to create book objects and add to newArray
        {                    
            //REGEX:
            // \ used for special characters
            // ? 0 or 1
            // * 0 or more
            // + 1 or more
            // ^start of string
            // website: regex101.com for testing  
            
            $aBook = new Book; 
            
            
            $bookIdRegex = "/(^BK\d+)\s+/";
            $idRegex = preg_match($bookIdRegex, $book, $matches);
            if ($idRegex){
                $id = $matches[1];                 
            }
            $aBook->id = $id;
            
            
            $bookTitleRegex = "/\s+([\w+\s*\d*-?,?\&?\(?\)?\!*]+)\s?/";
            $titleRegex = preg_match($bookTitleRegex, $book, $matches);
            if ($titleRegex){
                $title = $matches[0];         
            }
            $aBook->title = $title;
            
            
            $bookPriceRegex = "/([\$]\d+\.?\d*)/";
            $PriceRegex = preg_match($bookPriceRegex, $book, $matches);
            if ($PriceRegex){
                $price = $matches[0];
                $price = ltrim ($price, "$");
            }
            else 
            {
                $price = "0.00";
            }
            $aBook->price = $price;
            
        
            $newArray[] = $aBook;      
        }        
        return $newArray;
    }  
}
