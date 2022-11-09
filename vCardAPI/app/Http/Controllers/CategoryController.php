<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\VCard;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(VCard $vcard)
    {
        return CategoryResource::collection(Category::where('vcard','=',$vcard->phone_number)->get());
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function create(VCard $vcard, Request $request)
    {
        $category = new Category();
        $category->vcard = $vcard->phone_number;
        $category->type = $request->type;
        $category->name = $request->name;
        $category->save();
        return new CategoryResource($category);
    }
    public function delete(Category $category)
    {
        $category->delete();
        return new CategoryResource($category);
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }
}
