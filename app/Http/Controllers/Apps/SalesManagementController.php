<?php

namespace Erp\Http\Controllers\Apps;

use Illuminate\Http\Request;
use Erp\Http\Controllers\Controller;
use Erp\Models\Sale;
use Erp\Models\SaleItem;
use Erp\Models\Inventory;
use Erp\Models\InventoryMovement;
use Erp\Models\Product;
use Erp\Models\UomValue;
use Erp\Models\Contact;
use Erp\Models\PaymentMethod;
use Erp\Models\PaymentTerm;
use Erp\Models\Warehouse;
use Erp\Models\InternalTransfer;
use Erp\Models\InternalItems;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Auth;
use DB;
use PDF;

class SalesManagementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Can Access Sales');
        $this->middleware('permission:Can Create Data', ['only' => ['create','store']]);
        $this->middleware('permission:Can Edit Data', ['only' => ['edit','update']]);
        $this->middleware('permission:Can Delete Data', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $sales = Sale::orderBy('updated_at','desc')->get();
        $inventories = Inventory::join('products','products.id','=','inventories.product_id')
                                ->where('products.is_sale','1')
                                ->where('inventories.warehouse_id','!=','c40f889e-6fa3-43f2-bc2a-5fdded5aafed')
                                ->where('inventories.warehouse_id','!=','34437a64-ca03-47ff-be0c-63da5814484e')
                                ->get();
        
        return view('apps.pages.sales',compact('sales','inventories'));
    }

    public function create()
    {
        $customers = Contact::where('type_id','1')->pluck('name','ref_id')->toArray();
        $prod = Product::first();
        $products = Product::join('inventories','inventories.product_id','=','products.id')
                    ->where('products.is_sale','=','1')
                    ->where('inventories.warehouse_id','=','afdcd530-bb5e-462b-8dda-1371b9195903')
                    ->where('inventories.closing_amount','>=',$prod->min_stock)
                    ->pluck('products.name','products.id')
                    ->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        $locations = Warehouse::pluck('name','id')->toArray();
        return view('apps.input.salesNew',compact('customers','products','uoms','locations'));
    }

    public function searchProduct(Request $request)
    {
        $search = $request->get('product');
        
        $result = $products = Product::join('inventories','inventories.product_id','=','products.id')
                            ->where('inventories.warehouse_id','=','afdcd530-bb5e-462b-8dda-1371b9195903')
                            ->where('products.is_sale','=','1')
                            ->where('name','LIKE','%'.$search. '%')
                            ->orWhere('product_barcode','LIKE','%'.$search.'%')
                            ->select('products.id','products.name')
                            ->get();
        
        return response()->json($result);
    } 

    public function storeSales(Request $request)
    {
        $latestOrder = Sale::where('status_id','!=','af0e1bc3-7acd-41b0-b926-5f54d2b6c8e8')->count();
        $ref = 'PO/'.str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT).'/'.($request->input('client_code')).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';

        $details = Contact::where('ref_id',$request->input('client_code'))->first();

        $input = [
            'order_ref' => $ref,
            'client_code' => $request->input('client_code'),
            'client_id' => $details->id,
            'client_name' => $details->name,
            'delivery_date' => $request->input('delivery_date'),
            'billing_address' => $details->billing_address,
            'shipping_address' => $details->shipping_address,
            'delivery_date' => $request->input('delivery_date'),
            'created_by' => auth()->user()->name,
        ];
        
        $data = Sale::create($input);
        $items = $request->product;
        $quantity = $request->quantity;
        $sale_price = $request->sale_price;
        $uoms = $request->uom_id;
        $sale_id = $data->id;
        $discounts = $request->discount;
        foreach($items as $index=>$item) {
            $names = Product::where('name',$item)->orWhere('product_barcode',$item)->first();
            $items = SaleItem::create([
                'sales_id' => $sale_id,
                'product_id' => $names->id,
                'quantity' => $quantity[$index],
                'uom_id' => $uoms[$index],
                'sale_price' => $sale_price[$index],
                'sub_total' => ($sale_price[$index]) * ($quantity[$index]),
                'discount' => $discounts[$index],
            ]);
        }

        $qty = SaleItem::where('sales_id',$sale_id)->sum('quantity');
        $price = SaleItem::where('sales_id',$sale_id)->sum('sub_total');
        $disc = SaleItem::where('sales_id',$sale_id)->sum('discount');
        $tax = '10';
        $subtotal = ($price) - ($disc);
        if($details->tax == '1') {
            $saleData = DB::table('sales')
                        ->where('id',$sale_id)
                        ->update([
                            'quantity' => $qty,
                            'tax' => ($subtotal) * ($tax/100),
                            'total' => ($subtotal) + (($subtotal)*($tax/100)), 
                            ]);
        } else {
            $saleData = DB::table('sales')
                        ->where('id',$sale_id)
                        ->update(['quantity' => $qty, 'total' => $subtotal]);
        }
              
        $log = 'Sales Order '.($data->order_ref).' Berhasil Disubmit';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Sales Order '.($data->order_ref).' Berhasil Disubmit',
            'alert-type' => 'success'
        );
        return redirect()->route('sales.index')->with($notification);
    }

    public function salesStock($id)
    {
        $data = Sale::find($id);
        $details = SaleItem::join('inventories','inventories.product_id','=','sale_items.product_id')
                             ->join('products','products.id','sale_items.product_id')
                             ->where('sale_items.sales_id',$id)
                             ->get();
        
        return view('apps.show.salesStock',compact('details'))->renderSections()['content'];
    }

    public function processSales(Request $request,$id)
    {
        $data = Sale::find($id);
        $approve = $data->update([
            'status_id' => '458410e7-384d-47bc-bdbe-02115adc4449',
            'updated_by' => auth()->user()->name,
        ]);
        $reference = InternalTransfer::count();
        $refs = 'IT/'.str_pad($reference + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $transfers = InternalTransfer::create([
            'order_ref' => $refs,
            'from_id' => 'afdcd530-bb5e-462b-8dda-1371b9195903',
            'to_id' => '34437a64-ca03-47ff-be0c-63da5814484e',
            'status_id' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43',
            'created_by' => auth()->user()->name,
            'updated_by' => auth()->user()->name,
        ]);
        $items = SaleItem::where('sales_id',$id)->get();
        
        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$item->uom_id)->first();
            if($bases->is_parent == null) {
                $convertion = ($item->quantity) * ($bases->value); 
            } else {
                $convertion = $item->quantity;
            }
            $movements = InventoryMovement::where('product_id',$item->product_id)->where('warehouse_id','afdcd530-bb5e-462b-8dda-1371b9195903')->orderBy('updated_at','DESC')->first();
            $stocks = Inventory::where('product_id',$item->product_id)->get();
            $moveout = InventoryMovement::where('product_id',$item->product_id)->where('warehouse_id','afdcd530-bb5e-462b-8dda-1371b9195903')->orderBy('updated_at','DESC')->first();
            $movein = InventoryMovement::where('product_id',$item->product_id)->where('warehouse_id','34437a64-ca03-47ff-be0c-63da5814484e')->orderBy('updated_at','DESC')->first();
            $stocks = Inventory::where('product_id',$item->product_id)->first();
            $base = Inventory::where('product_id',$item->product_id)->where('warehouse_id','34437a64-ca03-47ff-be0c-63da5814484e')->first();

            $itemTransfer = InternalItems::create([
                'mutasi_id' => $transfers->id,
                'product_id' => $item->product_id,
                'quantity' => $convertion,
                'uom_id' => $item->uom_id,
            ]);
            
            if($base == null) {
                $inventories = Inventory::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => '34437a64-ca03-47ff-be0c-63da5814484e',
                    'min_stock' => '0',
                    'opening_amount' =>  $convertion,
                    'closing_amount' =>  $convertion,
                ]);
                $moveouts = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $inventories->id,
                    'reference_id' => $data->order_ref,
                    'product_id' => $item->product_id,
                    'incoming' => '0',
                    'outgoing' => $convertion,
                    'remaining' => ($moveout->remaining) - ($convertion),
                    'warehouse_id' => 'afdcd530-bb5e-462b-8dda-1371b9195903',
                ]);
                 $moveins = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $inventories->id,
                    'reference_id' => $data->order_ref,
                    'product_id' => $item->product_id,
                    'incoming' => $convertion,
                    'outgoing' => '0', 
                    'remaining' => $convertion,
                    'warehouse_id' => '34437a64-ca03-47ff-be0c-63da5814484e',
                ]);
            } else {
                $inventories = Inventory::where('product_id',$item->product_id)->where('warehouse_id','34437a64-ca03-47ff-be0c-63da5814484e')->first();
                $inventories->update([
                    'opening_amount' =>  $convertion,
                    'closing_amount' =>  ($base->closing_amount) + ($convertion),
                ]);
                $moveouts = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $inventories->id,
                    'reference_id' => $data->order_ref,
                    'product_id' => $item->product_id,
                    'incoming' => '0',
                    'outgoing' => $convertion,
                    'remaining' => ($moveout->remaining) - ($convertion),
                    'warehouse_id' => 'afdcd530-bb5e-462b-8dda-1371b9195903',
                ]);
                 $moveins = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $inventories->id,
                    'reference_id' => $data->order_ref,
                    'product_id' => $item->product_id,
                    'incoming' => $convertion,
                    'outgoing' => '0', 
                    'remaining' => ($movein->remaining) + ($convertion),
                    'warehouse_id' => '34437a64-ca03-47ff-be0c-63da5814484e',
                ]);
            }
            $results =  Inventory::where('product_id',$item->product_id)->where('warehouse_id','afdcd530-bb5e-462b-8dda-1371b9195903')->first();
            $results->update([
                'closing_amount' => ($results->closing_amount) - ($convertion),
            ]);
            
        }
        $log = 'Sales Order '.($data->order_ref).' Berhasil Diproses';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Sales Order '.($data->order_ref).' Berhasil Diproses',
            'alert-type' => 'success'
        );
        return redirect()->route('sales.index')->with($notification);
    }
 
    public function salesShow($id)
    {
        $sales = Sale::find($id);
        $data = SaleItem::where('sales_id',$id)->get();

        return view('apps.show.sales',compact('sales','data'));
    }

    public function salesPrint($id) 
    {
        $sales = Sale::find($id);
        $data = SaleItem::where('sales_id',$id)->get();

        $pdf = PDF::loadview('apps.print.salesOrder',compact('data','sales'));
        return $pdf->download('SO.pdf');
    }

    public function rejectedSale($id)
    {
        $data = Sale::find($id);
        $reject = $data->update([
            'status_id' => 'af0e1bc3-7acd-41b0-b926-5f54d2b6c8e8',
            'updated_by' => auth()->user()->name,
        ]);
        $log = 'Sales Order '.($data->order_ref).' Berhasil Ditolak';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Sales Order '.($data->order_ref).' Berhasil Ditolak',
            'alert-type' => 'success'
        );
        return redirect()->route('sales.index')->with($notification);
    }

}
