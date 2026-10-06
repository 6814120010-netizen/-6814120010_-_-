<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // ดึงสถิติต่างๆ จากฐานข้อมูล
        $totalCustomers = User::where('role', 'customer')->count();
        $totalEmployees = User::where('role', 'employee')->count();
        $totalPoints = User::where('role', 'customer')->sum('points');

        return view('admin.dashboard', compact('totalCustomers', 'totalEmployees', 'totalPoints'));
    }
}