<?php

class CreateController extends Controller
{
    public function index()
    {
        $this->requireVerify(); // Require verified user to create content
        require_once '../app/views/create.php';
    }


}