<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr as FacadesToastr;
use Brian2694\Toastr\Toastr as ToastrToastr;
use Toastr;
use Image;
use File;
use Illuminate\Support\Facades\DB;
use Str;

class CategoryController extends Controller
{
    function __construct()
    {
        //         $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','store']]);
        //         $this->middleware('permission:category-create', ['only' => ['create','store']]);
        //         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        //         $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Category::orderBy('id', 'DESC')->with('category')->get();
        // return $data;
        return view('backEnd.category.index', compact('data'));
    }
    public function create()
    {
        $categories = Category::orderBy('id', 'DESC')->select('id', 'name')->get();
        return view('backEnd.category.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $imageUrl = null;
            $iconUrl = null;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $imageName = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $imageName);
                $imageName = strtolower(preg_replace('/\s+/', '-', $imageName));

                $uploadPath = public_path('uploads/category/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $image->move($uploadPath, $imageName);
                $imageUrl = 'uploads/category/' . $imageName;
            }

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconName = time() . '-' . $icon->getClientOriginalName();
                $iconName = preg_replace('"\.(jpg|jpeg|png|webp|svg)$"', '.webp', $iconName);
                $iconName = strtolower(preg_replace('/\s+/', '-', $iconName));

                $uploadPath = public_path('uploads/category/icons/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $icon->move($uploadPath, $iconName);
                $iconUrl = 'uploads/category/icons/' . $iconName;
            }

            // Create new Category and assign values
            $category = new Category();
            $category->name = $request->name;
            $category->slug = str_replace('/', '', strtolower(preg_replace('/\s+/', '-', $request->name)));
            $category->status = $request->status;
            $category->parent_id = $request->parent_id ?? 0;
            $category->front_view = $request->front_view ? 1 : 0;
            $category->banner_image = $request->banner_image ? 1 : 0;
            $category->meta_title = $request->meta_title ?? null;
            $category->meta_description = $request->meta_description ?? null;
            $category->image = $imageUrl;
            $category->icon = $iconUrl;

            // Save category
            $category->save();

            DB::commit();

            Toastr::success('Success', 'Data inserted successfully');
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Toastr::error('Error', 'Something went wrong: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    // public function store(Request $request)
    // {

    //     dd($request->all());
    //     $this->validate($request, [
    //         'name' => 'required',
    //         'status' => 'required',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // Handle image upload using move()
    //         $image = $request->file('image');
    //         $imageUrl = null;

    //         if ($image) {
    //             $name = time() . '-' . $image->getClientOriginalName();
    //             $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name); // Optional: force .webp
    //             $name = strtolower(preg_replace('/\s+/', '-', $name));

    //             $uploadPath = public_path('uploads/category/');

    //             if (!file_exists($uploadPath)) {
    //                 mkdir($uploadPath, 0777, true);
    //             }

    //             $image->move($uploadPath, $name);
    //             $imageUrl = 'uploads/category/' . $name;
    //         }

    //         // Create new Category and assign data
    //         $category = new Category();
    //         $category->name = $request->name;
    //         $category->slug = str_replace('/', '', strtolower(preg_replace('/\s+/', '-', $request->name)));
    //         $category->status = $request->status;
    //         $category->parent_id = $request->parent_id ?? 0;
    //         $category->front_view = $request->front_view ? 1 : 0;
    //         $category->image = $imageUrl;

    //         // Save to database
    //         $category->save();

    //         DB::commit();

    //         FacadesToastr::success('Success', 'Data inserted successfully');
    //         return redirect()->route('categories.index');

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         FacadesToastr::error('Error', 'Something went wrong: ' . $e->getMessage());
    //         return redirect()->back()->withInput();
    //     }
    // }

    public function edit($id)
    {
        $edit_data = Category::find($id);
        $categories = Category::select('id', 'name')->get();
        return view('backEnd.category.edit', compact('edit_data', 'categories'));
    }

  

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $category = Category::findOrFail($request->id);

            $input = $request->all();

            // Slug setup
            $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
            $input['slug'] = str_replace('/', '', $input['slug']);

            // Defaults
            $input['parent_id'] = $request->parent_id ?? 0;
            $input['front_view'] = $request->front_view ? 1 : 0;
            $input['banner_image'] = $request->banner_image ? 1 : 0;
            $input['status'] = $request->status ? 1 : 0;
            $input['featured'] = $request->featured ?? "0";

            // === Handle Image Upload ===
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $imageName = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $imageName);
                $imageName = strtolower(preg_replace('/\s+/', '-', $imageName));
                $uploadPath = 'uploads/category/';

                if (!file_exists(public_path($uploadPath))) {
                    mkdir(public_path($uploadPath), 0755, true);
                }

                $image->move(public_path($uploadPath), $imageName);
                $imageUrl = $uploadPath . $imageName;

                // Delete old image
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }

                $input['image'] = $imageUrl;
            }

            // === Handle Icon Upload ===
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconName = time() . '-' . $icon->getClientOriginalName();
                $iconName = preg_replace('"\.(jpg|jpeg|png|webp|svg)$"', '.webp', $iconName);
                $iconName = strtolower(preg_replace('/\s+/', '-', $iconName));
                $iconPath = 'uploads/category/icons/';

                if (!file_exists(public_path($iconPath))) {
                    mkdir(public_path($iconPath), 0755, true);
                }

                $icon->move(public_path($iconPath), $iconName);
                $iconUrl = $iconPath . $iconName;

                // Delete old icon
                if ($category->icon && file_exists(public_path($category->icon))) {
                    unlink(public_path($category->icon));
                }

                $input['icon'] = $iconUrl;
            }

            // Update data
            $category->update($input);

            DB::commit();

            Toastr::success('Success', 'Data updated successfully');
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Toastr::error('Error', 'Update failed: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }



    public function inactive(Request $request)
    {
        $inactive = Category::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Category::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Category::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}