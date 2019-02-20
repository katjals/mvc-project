<?php

class RatingsController
{
    public function createRating()
    {
        //TODO get rating to see if booking has a rating or only have "rate" link if bike does not have rating
    
        if (!isset($_GET['id'])) {
            call('pages', 'error');
        } else {
            require_once(dirname(__DIR__).'/views/ratings/rate_bike.php');
        }
    }
    
    public function saveRating()
    {
        if (!empty($_POST['id']) || !empty($_POST['score'])) {
            $id = GenericCode::stripHtmlCharacters($_POST["id"]);
            $score = GenericCode::stripHtmlCharacters($_POST["score"]);
            $comment = GenericCode::stripHtmlCharacters($_POST["comment"]);
            
            Rating::saveRating($id, $score, $comment);
        
        } else {
            call('pages', 'error');
        }
    }
}