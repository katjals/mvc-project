<?php

class BikesController {
    public function index() {
        // we store all the posts in a variable
        $posts = Bike::all();
        require_once('views/bikes/index.php');
    }
    
    public function show(){
        // we expect a url of form ?controller=posts&actions=show&id=x
        // without an id we just redirect to the error page as we need the post id to find it in the db
        if (!isset($_GET['id']))
            return call('pages', 'error');
        
        // we use the given id to get the right post
        $post = Post::find($_GET['id']);
        require_once('views/bikes/show.php');
    }
}