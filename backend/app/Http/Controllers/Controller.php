<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // Como usamos policies, centralicé AuthorizesRequests en el controller base
    use AuthorizesRequests;
}
