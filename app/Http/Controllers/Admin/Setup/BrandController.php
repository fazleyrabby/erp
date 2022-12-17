<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\inventory\Brand;
use DB;
class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:brands.view', ['only' => ['index', 'getBrands']]);
        $this->middleware('permission:brands.store', ['only' => ['store']]);
        $this->middleware('permission:brands.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:brands.delete', ['only' => ['delete']]);
    }

    public function index()
    {
        return view('admin.setups.brands.view-brands');
    }
    public function getBrands(){
		$data = "";
		$brands = Brand::where('deleted','No')->orderBy('id', 'DESC')->get();
		$output = array('data' => array());
		$i=1;
		foreach ($brands as $brand) {
            $status = "";
            if($brand->status == 'Active'){
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$brand->status.'"></i></center>';
            }else{
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$brand->status.'"></i></center>';
            }
            $imageUrl = url('upload/brand_images/'.$brand->image);
		/*	$button = '<button type="button" onclick="editBrand('.$brand->id.')" class="btn btn-xs btn-warning btnEdit" title="Edit Brand" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="confirmDelete('.$brand->id.')" title="Delete Record"><i class="fa fa-trash"> </i></button>'; */

            $button = '<td style="width: 12%;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                <li class="action" onclick="editBrand('.$brand->id.')"  ><a  class="btn" ><i class="fas fa-edit"></i> Edit </a></li>
                                </li>
                            </li> 
                                <li class="action"><a   class="btn"  onclick="confirmDelete('.$brand->id.')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
                                </li>
                                </ul>
                            </div>
                        </td>';


			$output['data'][] = array(
				$i++. '<input type="hidden" name="id" id="id" value="'.$brand->id.'" />',
				$brand->name,
				'<img style="width:40px;" src="'.$imageUrl.'" alt="'.$brand->name.'" />',
				$status,
				$button
			);               
		}	
		return $output;
    }
    public function categoryWiseBrands(Request $request){
        if($request->id != ""){
            if($request->type == "purchase"){
                $brands = DB::table('brands')
                            ->join('products', 'products.brand_id', '=', 'brands.id')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->select('brands.id', 'brands.name')
                            ->where('brands.deleted','No')
                            ->where('products.category_id',$request->id)
                            ->orderBy('brands.id', 'DESC')
                            ->distinct()
                            ->get();
            }else{
                $brands = DB::table('brands')
                            ->join('products', 'products.brand_id', '=', 'brands.id')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->select('brands.id', 'brands.name')
                            ->where('brands.deleted','No')
                            ->where('products.category_id',$request->id)
                            ->where('products.current_stock','>',0)
                            ->orderBy('brands.id', 'DESC')
                            ->distinct()
                            ->get();
            }
        }else{
            if($request->type == "purchase"){
                $brands = Brand::where('deleted','No')->orderBy('id', 'DESC')->get();
            }else{
                $brands = Brand::where('deleted','No')->where('products.current_stock','>',0)->orderBy('id', 'DESC')->get();
            }

        }
        return $brands;
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|unique:brands,name|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u'
          ]);

        if($request->hasFile('image')){

            $request->validate([
                'image'   =>  'image|max:2048'
            ]);
			$brandImage = $request->file('image');
            $name = $brandImage->getClientOriginalName();
            $uploadPath = 'upload/brand_images/';
            $imageUrl = $uploadPath.$name;
            $imageName = time().$name;
            $brandImage->move($uploadPath, $imageName);
        }else{
            $imageName = "no_image.png";
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->image = $imageName;
        $brand->created_by = auth()->user()->id;
        $brand->created_date = date('Y-m-d H:i:s');
        $brand->deleted = 'No';
        $brand->save();
        return response()->json(['success'=>'Brand saved successfully']);
    }

    public function edit(Request $request){
		$brand = Brand::find($request->id);
		return $brand;
    }
    
    public function update(Request $request)
      {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:brands,name,'.$request->id
        ]);
        $brand =Brand::find($request->id);
        $brand->name = $request->name;

        if ($request->removeImage == "1"){
			$brand->image = "no_image.png";
		}
        else if($request->hasFile('image')){
            $request->validate([
                'image'   =>  'image|max:2048'
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
		return response()->json(['success'=>'Brand updated successfully']);
    }

    public function delete(Request $request)
    {
        $brand =Brand::find($request->id);
        $brand->deleted ='Yes';
        $brand->name = $brand->name.'-Deleted-'.$request->id;
        $brand->deleted_by = auth()->user()->id;
        $brand->deleted_date = date('Y-m-d H:i:s');
        $brand->save();
        return response()->json(['success'=>'Brand deleted successfully']);
    }
}