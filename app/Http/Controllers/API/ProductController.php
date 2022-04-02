<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseFormatter;

class ProductController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id) {
            $product = Product::with(['galleries', 'category'])->find($id);
            if($product) {
                return ResponseFormatter::success($product, 'Product retrieved successfully');
            } else {
                return ResponseFormatter::error('Product not found', 404);
            }
        }

        $product = Product::with(['galleries', 'category']);
        
        if($name) {
            $product->where('name', 'like', '%'.$name.'%');
        }

        if($description) {
            $product->where('description', 'like', '%'.$description.'%');
        }

        if($tags) {
            $product->where('tags', 'like', '%'.$tags.'%');
        }

        if($categories) {
            $product->where('category_id', $categories);
        }

        if($price_from) {
            $product->where('price', '>=', $price_from);
        }

        if($price_to) {
            $product->where('price', '<=', $price_to);
        }

        if($limit) {
            $product->limit($limit);
        }
        
        return ResponseFormatter::success(
            $product->paginate($limit),
            'Products retrieved successfully'
        );
    }
}
