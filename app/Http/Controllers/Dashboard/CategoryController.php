<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Validation\Rule;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request )

    {
        $categories =Category::when($request->search,function($q) use ($request){
            return $q->whereTranslationLike('name', '%' .  $request->search . '%');

        })->latest()->paginate(4);
        return view('dashboard.categories.index',compact('categories'));

    }


    public function create()
    {
        return view('dashboard.categories.create');

    }


    public function store(Request $request)
    {


        $rouls=[];
        foreach (config('translatable.locales') as $locale){
           $rouls += [$locale. '.name' => ['required', Rule::unique('category_translations','name')]];
        }
        $request->validate($rouls);

        Category::create($request->all());
        session()->flash('success', __('site.added_successfully'));
        return  redirect()->route('dashboard.categories.index');
    }




       public function edit(category $category)
    {
        return view('dashboard.categories.edit',compact('category'));
    }


    public function update(Request $request, category $category)
    {
        $rouls=[];
        foreach (config('translatable.locales') as $locale){
           $rouls += [$locale. '.name' => ['required', Rule::unique('category_translations','name')->ignore($category->id,'category_id')]];
        }
        $request->validate($rouls);
        $category->update($request->all());
        session()->flash('success', __('site.updated_successfully'));
        return  redirect()->route('dashboard.categories.index');
    }

    public function destroy(category $category)
    {
        $category->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return  redirect()->route('dashboard.categories.index');
    }
}
