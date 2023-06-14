<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class LayoutComposer
{
    public function compose(View $view)
    {
      $categories = Category::all();
      $menus =Category::with('allChildren')->whereNull('parent_id')->get();
      $data = [
        'categories' => $categories,
        'menus' => $menus
    ];
      $view->with($data);
    }
}
