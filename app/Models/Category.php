<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image', 'status'];

    protected static $category;
    protected static $categoryImage;
    protected static $imageDirectory;
    protected static $imageName;
    protected static $imageUrl;

    public static function getCategoryImage($request)
    {
        self::$categoryImage = $request->file('image');
        self::$imageName = 'category-image'.time().'.'.self::$categoryImage->getClientOriginalExtension();
        self::$imageDirectory = 'category-images/';
        self::$categoryImage->move(self::$imageDirectory, self::$imageName);
        return self::$imageDirectory.self::$imageName;
    }

    public static function newCategory($request)
    {
        self::$category = new Category();
        self::$category->name        = $request->name;
        self::$category->description = $request->description;
        self::$category->image       = self::getCategoryImage($request);
        self::$category->status      = $request->status;
        self::$category->save();
    }

    public static function updateCategory($request, $id)
    {
        self::$category = Category::find($id);

        if ($request->hasFile('image'))
        {
            if (file_exists(self::$category->image))
            {
                unlink(self::$category->image);
            }
            self::$imageUrl = self::getCategoryImage($request);
        } else {
            self::$imageUrl = self::$category->image;
        }

        self::$category->name        = $request->name;
        self::$category->description = $request->description;
        self::$category->image       = self::$imageUrl;
        self::$category->status      = $request->status;
        self::$category->save();
    }

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}