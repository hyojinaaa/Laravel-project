<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Products;
use Intervention\Image\ImageManager;

class ShopController extends Controller
{
    public function index(){

        // Grab all the products from model
        $AllProducts = Products::paginate(6);

        // Show all the products through the view
        return view('shop.index', compact('AllProducts'));
    }

    public function add(){
    	return view('shop.add');
    }

    public function submit(Request $request){
    	$this->validate($request, [
    		'product_title' => 'required|min:1|max:255',
    		'product_description' => 'required|min:10',
            'product_image' => 'required|image',
            'product_price' => 'required|numeric',
            'product_quantity' => 'required|numeric',
    		]);

        $newProduct = new Products();
        $newProduct->title = $request->product_title;
        $newProduct->description = $request->product_description;
        $newProduct->price = $request->product_price;
        $newProduct->quantity = $request->product_quantity;

        $manager = new ImageManager();

        $folder = './images/products';
        $imagename = preg_replace("/[^0-9a-zA-Z]/", '', $request->product_title);

        $productImage = $manager->make($request->product_image);
        $productImage->fit(100,100);
        $productImage->save($folder.'/'.$imagename.'.jpg',100);

        // Save image for database
        $newProduct->image = $imagename.'.jpg';

        $newProduct->save();
        return redirect('/Shop');
    }

    public function show($id){
        $product = Products::findOrFail($id);
        return view('shop.show', compact('product'));
    }

    public function delete($id){
         $product = Products::findOrFail($id);
         $productImage = $product['image'];
         unlink("./images/Products/$productImage");
         $product->delete();
         return redirect('/Shop');
     }
}
