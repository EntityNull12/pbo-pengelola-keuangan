<?php

namespace App\Controllers;

use App\Models\YourModel; // If you are using a model

class About extends BaseController
{
    public function about()
    {
        return view('about'); // Replace with the actual view file
    }
}
