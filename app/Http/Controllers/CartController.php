<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use App\Cart;
use App\Products;
use App\User;

class CartController extends Controller
{
    public function index(){
    	$UserID = \Auth::user()->id;
    	$user = User::where('id', '=', $UserID)->firstOrFail();
    	$Cart = $user->cart;
    	return view('cart.index', compact('Cart'));
    }

    public function add(Request $request, $id){
    	$this->validate($request, [
    		'size' => 'required',
    		'quantity' => 'required|numeric'
    	]);

    	// Check to see if add to cart was pressed
    	if( isset($_POST['addtocart']) ){

    		
    		$product = Products::findOrFail($id);

    		// Check to see if there is enough stock for this product
    		if($request->quantity > $product['quantity']){
    			Session::flash('LowStock', 'Sorry, there is not enough stock to process your order');
    			// In that case, Stop at here
    			return redirect("/Shop/$id");
			}

			// Checking the stock of the product
			$productFound = false;

    		// Get the user ID
    		$UserID = \Auth::user()->id;
    		// Get the Subtotal
    		$subtotal = $request->quantity * $product['price'];

    		// Check to see if product is already in database
    		$cart = Cart::where('user_id', '=', $UserID)->get();
    		// Loop through the cart and is if there is a matching product ID and a matching Size
    		foreach($cart as $cartItem){
    			if( ($cartItem['product_id'] == $id) & ($cartItem['size'] == $request->size) ){
    				$productFound = true;
    			}
    		}

    		// Change the stock quantity of the products
    		$product->quantity = $product['quantity'] - $request->quantity;
    		$product->save();

    		if( $productFound == true) {
    			
    			// Product already exists in the cart
    			// Update the quantity of the cart item
    			foreach($cart as $cartItem){
    				if( ($cartItem['product_id'] == $id) & ($cartItem['size'] == $request->size) ){
    					$cartItem->quantity = $cartItem['quantity'] + $request->quantity;
    					$cartItem->subtotal = $cartItem['subtotal'] + $subtotal;
    					$cartItem->save();
    					break;			
    				}	
    			}		
    		} else {
				
				// Add Product to cart
    			$Cart = new Cart();
    			$Cart->user_id = $UserID;
    			$Cart->product_id = $id;
    			$Cart->size = $request->size;
    			$Cart->quantity = $request->quantity;
    			$Cart->subtotal = $subtotal;
    			$Cart->save();
    		}

			return redirect('/Cart');

		};
	}

	public function remove($id){
		$cartItem = Cart::where('id', '=', $id)->firstOrFail();
		$product = Products::where('id', '=', $cartItem['product_id'])->firstOrFail();
		$product->quantity = $product['quantity'] + $cartItem['quantity'];
		$product->save();
		$cartItem->delete();

		Session::flash('RemoveCart', 'Item was successfully removed from your cart');
		return redirect('/Cart');
	}

}