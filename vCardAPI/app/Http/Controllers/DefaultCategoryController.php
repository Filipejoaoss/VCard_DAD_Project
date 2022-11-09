<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Http\Requests\StoreDefaultCategoryRequest;
use App\Http\Requests\UpdateDefaultCategoryRequest;

class DefaultCategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(DefaultCategory::all());
    }

    public function show(int $id)
    {
        $category = DefaultCategory::find($id);
        return new CategoryResource($category);
    }

    public function store(StoreDefaultCategoryRequest $request)
    {
        $defaultCategory = new DefaultCategory();
        $defaultCategory->fill($request->validated());
        $defaultCategory->save();
        return new CategoryResource($defaultCategory);
    }

    public function update(UpdateDefaultCategoryRequest $request, DefaultCategory $defaultCategory)
    {
        $defaultCategory->update($request->validated());
        return new CategoryResource($defaultCategory);
    }

    public function destroy(DefaultCategory $defaultCategory)
    {
        $defaultCategory->delete();
        return new CategoryResource($defaultCategory);
    }
}
