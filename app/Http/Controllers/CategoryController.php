<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $category = $this->selectCategory();

        return view('category.list_category', ['category' => $category, 'auth_status' => Auth::check()]);
    }

    public function openCategory($category)
    {
        $events = $this->selectEventsCategory($category);
        
        if (count($events) > 0)
        {
            return view('category.name_category', ['events' => $events, 'auth_status' => Auth::check()]);
        }
        else
        {
            return view('category.error_category', ['auth_status' => Auth::check()]);
        }
    }

    public function selectEventsCategory($category)
    {
        return Event::select('id','event_name')->where('name_category', $category)->get();
    }

    public function selectCategory()
    {
        return Event::select('name_category')->distinct()->get();
    }
}
