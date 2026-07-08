<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\inventory\Brand;
use DB;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:brands.view', ['only' => ['index', 'getBrands']]);
        $this->middleware('permission:brands.store', ['only' => ['store']]);
        $this->middleware('permission:brands.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:brands.delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $query = Brand::where('deleted', 'No');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $brands = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.setups.brands.view-brands', compact('brands'));
    }

    public function categoryWiseBrands(Request $request)
    {
        if ($request->id != '') {
            if ($request->type == 'purchase') {
                $brands = DB::table('brands')
                    ->join('products', 'products.brand_id', '=', 'brands.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('brands.id', 'brands.name')
                    ->where('brands.deleted', 'No')
                    ->where('products.category_id', $request->id)
                    ->orderBy('brands.id', 'DESC')
                    ->distinct()
                    ->get();
            } else {
                $brands = DB::table('brands')
                    ->join('products', 'products.brand_id', '=', 'brands.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('brands.id', 'brands.name')
                    ->where('brands.deleted', 'No')
                    ->where('products.category_id', $request->id)
                    ->where('products.current_stock', '>', 0)
                    ->orderBy('brands.id', 'DESC')
                    ->distinct()
                    ->get();
            }
        } else {
            if ($request->type == 'purchase') {
                $brands = Brand::where('deleted', 'No')->orderBy('id', 'DESC')->get();
            } else {
                $brands = Brand::where('deleted', 'No')->where('products.current_stock', '>', 0)->orderBy('id', 'DESC')->get();
            }

        }

        return $brands;
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|unique:brands,name|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);

        if ($request->hasFile('image')) {

            $request->validate([
                'image' => 'image|max:2048',
            ]);
            $brandImage = $request->file('image');
            $name = $brandImage->getClientOriginalName();
            $uploadPath = 'upload/brand_images/';
            $imageUrl = $uploadPath.$name;
            $imageName = time().$name;
            $brandImage->move($uploadPath, $imageName);
        } else {
            $imageName = 'no_image.png';
        }

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->image = $imageName;
        $brand->created_by = auth()->user()->id;
        $brand->created_date = date('Y-m-d H:i:s');
        $brand->deleted = 'No';
        $brand->save();

        return response()->json(['success' => 'Brand saved successfully']);
    }

    public function edit(Request $request)
    {
        $brand = Brand::find($request->id);

        return $brand;
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:brands,name,'.$request->id,
        ]);
        $brand = Brand::find($request->id);
        $brand->name = $request->name;

        if ($request->removeImage == '1') {
            $brand->image = 'no_image.png';
        } elseif ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);
            $brandImage = $request->file('image');
            $name = $brandImage->getClientOriginalName();
            $uploadPath = 'upload/brand_images/';
            $imageUrl = $uploadPath.$name;
            $imageName = time().$name;
            $brandImage->move($uploadPath, $imageName);
            $brand->image = $imageName;
        }

        $brand->status = $request->status;
        $brand->updated_by = auth()->user()->id;
        $brand->updated_date = date('Y-m-d H:i:s');
        $brand->save();

        return response()->json(['success' => 'Brand updated successfully']);
    }

    public function delete(Request $request)
    {
        $brand = Brand::find($request->id);
        $brand->deleted = 'Yes';
        $brand->name = $brand->name.'-Deleted-'.$request->id;
        $brand->deleted_by = auth()->user()->id;
        $brand->deleted_date = date('Y-m-d H:i:s');
        $brand->save();

        return response()->json(['success' => 'Brand deleted successfully']);
    }
}
