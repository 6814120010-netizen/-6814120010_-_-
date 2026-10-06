<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    
    // แสดงรายการสินค้าทั้งหมด (พร้อมระบบกรองหมวดหมู่)
    public function index(Request $request)
    {
        $query = Product::with('category');

        // กรองตามหมวดหมู่ถ้ามีการเลือก
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();
        $categories = Category::all(); // ดึงหมวดหมู่ทั้งหมดมาแสดงใน Dropdown

        return view('admin.products.index', compact('products', 'categories'));
    }

    // หน้าฟอร์มเพิ่มสินค้าใหม่
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // บันทึกสินค้าใหม่
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', 'unique:products,code'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'category_id.required' => 'กรุณาเลือกหมวดหมู่สินค้า',
            'code.required' => 'กรุณากรอกรหัสสินค้า/บาร์โค้ด',
            'code.unique' => 'รหัสสินค้านี้มีในระบบแล้ว',
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาขาย',
            'stock.required' => 'กรุณากรอกจำนวนสต็อก',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'เพิ่มสินค้าใหม่เรียบร้อยแล้ว');
    }

    // ลบสินค้า (Soft Delete)
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'ลบรายการสินค้าเรียบร้อยแล้ว');
    }
// แสดงฟอร์มแก้ไขสินค้า
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }


    // บันทึกการแก้ไขสินค้า
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', 'unique:products,code,' . $product->id],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'category_id.required' => 'กรุณาเลือกหมวดหมู่สินค้า',
            'code.required' => 'กรุณากรอกรหัสสินค้า',
            'code.unique' => 'รหัสสินค้านี้ซ้ำกับรายการอื่น',
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาขาย',
            'stock.required' => 'กรุณากรอกจำนวนสต็อก',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ];

        // ถ้ามีการเปลี่ยนรูปภาพใหม่ ให้ลบรูปเดิมแล้วเซฟรูปใหม่
        if ($request->hasFile('image')) {
            if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว');
        
    }
    
}