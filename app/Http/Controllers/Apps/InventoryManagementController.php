<?php

namespace Erp\Http\Controllers\Apps;

use Illuminate\Http\Request;
use Erp\Http\Controllers\Controller;
use Erp\Models\Product;
use Erp\Models\Inventory;
use Erp\Models\InventoryMovement;
use Erp\Models\Warehouse;
use Erp\Models\InternalTransfer;
use Erp\Models\InternalItems;
use Erp\Models\Purchase;
use Erp\Models\PurchaseItem;
use Erp\Models\Delivery;
use Erp\Models\DeliveryService;
use Erp\Models\Sale;
use Erp\Models\SaleItem;
use Erp\Models\UomValue;
use Erp\Models\ReturSale;
use Erp\Models\ReturItem;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Auth;
use PDF;

class InventoryManagementController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:Can View Data');
         $this->middleware('permission:Can Create Data', ['only' => ['create','store']]);
         $this->middleware('permission:Can Edit Data', ['only' => ['edit','update']]);
         $this->middleware('permission:Can Delete Data', ['only' => ['destroy']]);
    }

    public function inventoryIndex()
    {
        $data = Inventory::orderBy('id','asc')->get();
        $products = Product::pluck('name','id')->toArray();
        $locations = Warehouse::pluck('name','id')->toArray();

        return view('apps.pages.inventories',compact('data','products','locations'));
    }

    public function stockCard(Request $request,$id)
    {
        $source = Inventory::where('id',$id)->first();
        $data = InventoryMovement::where('product_id',$source->product_id)
                                ->where('warehouse_id',$source->warehouse_id)
                                ->paginate(5);
        
        return view('apps.show.stockCard',compact('data'))->renderSections()['content'];
    }

    public function stockPrint(Request $request,$id)
    {
        $source = Inventory::where('id',$id)->first();
        $data = InventoryMovement::where('product_id',$source->product_id)
                                ->where('warehouse_id',$source->warehouse_id)
                                ->get();
        $filename = Product::where('id',$source->product_id)->first();
        
        $pdf = PDF::loadview('apps.print.stockCard',compact('data','source'));
        return $pdf->download('Stock Card '.$filename->name.'.pdf');
    }

    public function inventoryAdjustIndex()
    {
        $data = Inventory::orderBy('id','asc')->get();
        
        return view('apps.pages.inventoryAdjustment',compact('data'));
    }

    public function makeAdjust($id)
    {
        $data = Inventory::find($id);

        return view('apps.edit.makeAdjust',compact('data'))->renderSections()['content'];
    }

    public function storeAdjust(Request $request,$id)
    {
        $this->validate($request, [
            'adjust_type' => 'required|not_in:0',
            'adjust_amount' => 'required|numeric',
            'notes' => 'required',
        ]);
        $ref = 'ADJ/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        
        $products = Product::where('id',$request->input('product_id'))->first();
        $source = Inventory::where('product_id',$request->input('product_id'))->where('warehouse_id',$request->input('warehouse_id'))->first();
        $sourceMove = InventoryMovement::where('product_id',$request->input('product_id'))->where('warehouse_id',$request->input('warehouse_id'))->orderBy('updated_at','DESC')->first();
        if ($sourceMove == null && $request->input('adjust_type') == '1') {
            $results = Inventory::where('product_id',$request->input('product_id'))->where('warehouse_id',$request->input('warehouse_id'))->update([
                'closing_amount' => ($source->closing_amount) + ($request->input('adjust_amount')),
            ]);
            $movements = InventoryMovement::create([
                'reference_id' => $ref,
                'type' => '1',
                'inventory_id' => $id,
                'product_id' => $request->input('product_id'),
                'warehouse_id' => $request->input('warehouse_id'),
                'incoming' => $request->input('adjust_amount'),
                'outgoing' => '0',
                'remaining' => $request->input('adjust_amount'),
                'notes' => $request->input('notes'),
                ]);
            
        } elseif ($sourceMove == null && $request->input('adjust_type') == '2') {
            $results = Inventory::where('product_id',$request->input('product_id'))->where('warehouse_id',$request->input('warehouse_id'))->update([
                'closing_amount' => ($source->closing_amount) + ($request->input('adjust_amount')),
            ]);
            $movements = InventoryMovement::create([
                'reference_id' => $ref,
                'type' => '1',
                'inventory_id' => $id,
                'product_id' => $request->input('product_id'),
                'warehouse_id' => $request->input('warehouse_id'),
                'incoming' => '0',
                'outgoing' => $request->input('adjust_amount'),
                'remaining' => $request->input('adjust_amount'),
                'notes' => $request->input('notes'),
                ]);
        } elseif ($request->input('adjust_type') == '1') {
            $results = Inventory::where('product_id',$request->input('product_id'))->where('warehouse_id',$request->input('warehouse_id'))->update([
                'closing_amount' => ($source->closing_amount) + ($request->input('adjust_amount')),
            ]);
            $movements = InventoryMovement::create([
                'reference_id' => $ref,
                'type' => '1',
                'inventory_id' => $id,
                'product_id' => $request->input('product_id'),
                'warehouse_id' => $request->input('warehouse_id'),
                'incoming' => $request->input('adjust_amount'),
                'outgoing' => '0',
                'remaining' => ($sourceMove->remaining) + ($request->input('adjust_amount')),
                'notes' => $request->input('notes'),
                ]);
        } else {
            $results = Inventory::where('product_id',$request->input('product_id'))->where('warehouse_id',$request->input('warehouse_id'))->update([
                'closing_amount' => ($source->closing_amount) - ($request->input('adjust_amount')),
            ]);
            $movements = InventoryMovement::create([
                'reference_id' => $ref,
                'type' => '1',
                'inventory_id' => $id,
                'product_id' => $request->input('product_id'),
                'warehouse_id' => $request->input('warehouse_id'),
                'incoming' => '0',
                'outgoing' => $request->input('adjust_amount'),
                'remaining' => ($sourceMove->remaining) - ($request->input('adjust_amount')),
                'notes' => $request->input('notes'),
                ]);
        }
            
        $log = 'Stok '.($products->name).' Berhasil Disesuaikan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Stok '.($products->name).' Berhasil Disesuaikan',
            'alert-type' => 'success'
        );

        return redirect()->route('inventory.adjust')->with($notification);
    }

    public function receiptIndex()
    {
        $data = Purchase::where('status','458410e7-384d-47bc-bdbe-02115adc4449')->pluck('order_ref','id')->toArray();
        $locations = Warehouse::pluck('name','id')->toArray();
        $details = Purchase::orderBy('created_at','ASC')->get();

        return view('apps.pages.purchaseReceipt',compact('data','locations','details'));
    }
    public function purchaseReceipt(Request $request)
    {
        $input = [
            'order_ref' => $request->input('order_ref'),
        ];

        $data = Purchase::where('id',$request->input('order_ref'))->first();
        $items = PurchaseItem::where('purchase_id',$request->input('order_ref'))->get();
        
        $in = Purchase::where('id',$request->input('order_ref'))->update([
            'status' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43',
        ]);
        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$item->uom_id)->first();
            if($bases->is_parent == null) {
                $convertion = ($item->quantity) * ($bases->value); 
            } else {
                $convertion = $item->quantity;
            }
            $sources = Inventory::where('product_id',$item->product_id)->orderBy('updated_at','DESC')->get();
            $moves = InventoryMovement::where('product_id',$item->product_id)->orderBy('updated_at','DESC')->first();
            $inventories = Inventory::updateOrCreate([
                'product_id' => $item->product_id,
                'warehouse_id' => $request->input('warehouse_id')],[
                'opening_amount' => $sources[0]->closing_amount,
                'closing_amount' => ($sources[0]->closing_amount) + $convertion,
            ]);
            
            if($moves === null) {
                $movements = InventoryMovement::create([
                    'product_id' => $inventories->product_id,
                    'warehouse_id' => $inventories->warehouse_id,
                    'type' => '3',
                    'inventory_id' => $inventories->id,
                    'reference_id' => $data->order_ref,
                    'incoming' => $convertion,
                    'remaining' => $convertion,
                ]);
            } else {
                $movements = InventoryMovement::create([
                    'product_id' => $inventories->product_id,
                    'warehouse_id' => $inventories->warehouse_id,
                    'type' => '3',
                    'inventory_id' => $inventories->id,
                    'reference_id' => $data->order_ref,
                    'incoming' => $convertion,
                    'remaining' => ($moves->remaining) + $convertion,
                ]);
            }
        }
        $log = 'Pembelian '.($data->order_ref).' Berhasil Diterima';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pembelian '.($data->order_ref).' Berhasil Diterima',
            'alert-type' => 'success'
        );

        return redirect()->route('receipt.index')->with($notification);
    }

    public function internTransfer()
    {
        $data = InternalTransfer::orderBy('created_at','DESC')->get();
       
        return view('apps.pages.internalTransfer',compact('data'));
    }

    public function addTransfer()
    {
        $userLocation = auth()->user()->warehouse_id;
        $locations = Warehouse::where('id','!=',$userLocation)->pluck('name','id')->toArray();
        $products = Product::pluck('name','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        
        return view('apps.input.internalTransfer',compact('locations','products','uoms','userLocation'));
    }

    public function internStore(Request $request)
    {
        $items = $request->product_id;
        $quantity = $request->quantity;
        $uom = $request->uom_id;
        $reference = InternalTransfer::count();
        $ref = 'IT/'.str_pad($reference + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $data = [
            'order_ref' => $ref,
            'from_id' => $request->input('from_id'),
            'to_id' => $request->input('to_id'),
            'created_by' => auth()->user()->name,
        ];
        $internal = InternalTransfer::create($data);
        
        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$uom[$index])->first();
            if($bases->is_parent == null) {
                $convertion = ($quantity[$index]) * ($bases->value); 
            } else {
                $convertion = $quantity[$index];
            }
            $refProduct = Product::where('id',$item)->first();
            $base = Inventory::where('product_id',$item)->where('warehouse_id',$internal->to_id)->first();
            $source = Inventory::where('product_id',$item)->where('warehouse_id',$internal->from_id)->first();
            $from = InventoryMovement::where('product_id',$item)->where('warehouse_id',$internal->from_id)->orderBy('updated_at','DESC')->first();
            $to = InventoryMovement::where('product_id',$item)->where('warehouse_id',$internal->to_id)->orderBy('updated_at','DESC')->first();
           
            $items = InternalItems::create([
                'product_id' => $item,
                'mutasi_id' => $internal->id,
                'quantity' => $quantity[$index],
                'uom_id' => $uom[$index],
            ]);
            if($base == null) {
                $dataInvent = Inventory::create([
                    'product_id' => $item,
                    'warehouse_id' => $internal->to_id,
                    'min_stock' => $refProduct->min_stock,
                    'opening_amount' => '0',
                    'closing_amount' => $convertion,
                ]);
                $outcome = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $dataInvent->id,
                    'reference_id' => $ref,
                    'product_id' => $dataInvent->product_id,
                    'warehouse_id' => $from->warehouse_id,
                    'incoming' => '0',
                    'outgoing' => $convertion,
                    'remaining' => ($from->remaining) - ($convertion),
                ]);

                $income = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $dataInvent->id,
                    'reference_id' => $ref,
                    'product_id' => $dataInvent->product_id,
                    'warehouse_id' => $dataInvent->warehouse_id,
                    'incoming' => $convertion,
                    'outgoing' => '0',
                    'remaining' => $convertion,
                ]);
                $updateInvent = Inventory::where('product_id',$item)->where('warehouse_id',$internal->from_id)->update([
                    'closing_amount' => ($source->closing_amount) - ($convertion),
                ]);
            } else {
                $dataInvent = $base->update([
                    'closing_amount' => ($base->closing_amount) + ($convertion),
                ]);
                $outcome = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $base->id,
                    'reference_id' => $ref,
                    'product_id' => $base->product_id,
                    'warehouse_id' => $from->warehouse_id,
                    'incoming' => '0',
                    'outgoing' => $convertion,
                    'remaining' => ($from->remaining) - ($convertion),
                ]);

                $updateInvent = Inventory::where('product_id',$item)->where('warehouse_id',$internal->from_id)->update([
                    'closing_amount' => ($source->closing_amount) - ($convertion),
                ]);
            }
            if($to == null) {
                $income = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $base->id,
                    'reference_id' => $ref,
                    'product_id' => $base->product_id,
                    'warehouse_id' => $base->warehouse_id,
                    'incoming' => $convertion,
                    'outgoing' => '0',
                    'remaining' => $convertion,
                ]);
            } else {
                $income = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $base->id,
                    'reference_id' => $ref,
                    'product_id' => $base->product_id,
                    'warehouse_id' => $base->warehouse_id,
                    'incoming' => $convertion,
                    'outgoing' => '0',
                    'remaining' => ($base->remaining) + ($convertion),
                ]);
            }
        }
        $log = 'Internal Transfer '.($internal->order_ref).' Berhasil Dibuat';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Internal Transfer '.($internal->order_ref).' Berhasil Dibuat',
            'alert-type' => 'success'
        );
        
        return redirect()->route('transfer.index')->with($notification);
    }

    public function transferApprove(Request $request,$id)
    {
        $data = InternalTransfer::find($id);
        $data->update([
            'status_id' => '458410e7-384d-47bc-bdbe-02115adc4449',
            'updated_by' => auth()->user()->name,
        ]);
        $log = 'Internal Transfer '.($data->order_ref).' Berhasil Diproses';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Internal Transfer '.($data->order_ref).' Berhasil Diproses',
            'alert-type' => 'success'
        );

        return redirect()->route('transfer.index')->with($notification);
    }

    public function transferView($id)
    {
        $source = InternalTransfer::find($id);
        $details = InternalItems::where('mutasi_id',$id)->get();
        
        return view('apps.show.internalTransfer',compact('details'))->renderSections()['content'];
    }

    public function transferAccept(Request $request,$id)
    {
        $data = InternalTransfer::find($id);
        $accept = $data->update([
            'status_id' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43',
            'received_by' => auth()->user()->name,
        ]);
        $log = 'Internal Transfer '.($data->order_ref).' Berhasil Diterima';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Internal Transfer '.($data->order_ref).' Berhasil Diterima',
            'alert-type' => 'success'
        );

        return redirect()->route('transfer.index')->with($notification);
    }

    public function deliveryIndex()
    {
        $data = Delivery::get();
        $sales = Sale::where('status_id','458410e7-384d-47bc-bdbe-02115adc4449')->pluck('order_ref','id')->toArray();
        $services = DeliveryService::pluck('delivery_name','id')->toArray();

        return view('apps.pages.deliveryOrder',compact('data','sales','services'));
    }

    public function deliveryOrder(Request $request)
    {
        $this->validate($request, [
            'sales_ref' => 'required',
            'delivery_service' => 'required',
            'delivery_cost' => 'required',
        ]);

        $input = $request->all();
        
        $lastOrder = Delivery::count();
        $refs = 'DO/'.str_pad($lastOrder + 1, 4, "0", STR_PAD_LEFT).'/'.'FTI'.'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $salesRefs = Sale::where('id',($request->input('sales_ref')))->first();

        $orders = Delivery::create([
            'order_ref' => $refs,
            'sales_ref' => $salesRefs->order_ref,
            'delivery_service' => $request->input('delivery_service'),
            'delivery_cost' => $request->input('delivery_cost'),
            'created_by' => auth()->user()->name,
        ]);
        $moves = SaleItem::where('sales_id',$salesRefs->id)->get();
        $source = InventoryMovement::where('product_id',$moves[0]->product_id)->where('warehouse_id','34437a64-ca03-47ff-be0c-63da5814484e')->orderBy('created_at','DESC')->first();
        foreach($moves as $index=>$val) {
            $movements = InventoryMovement::create([
                'type' => '5',
                'inventory_id' => $source->inventory_id,
                'reference_id' => $refs,
                'product_id' => $val->product_id,
                'outgoing' => $val->quantity,
                'remaining' => ($source->remaining) - ($val->quantity),
                'warehouse_id' => '34437a64-ca03-47ff-be0c-63da5814484e',
            ]);
        };
        foreach($moves as $index=>$val) {
            Inventory::where('product_id',$val->product_id)->where('warehouse_id',$movements->warehouse_id)->update([
                'closing_amount' => $movements->remaining,
            ]);
        }

        $log = 'Delivery Order '.($orders->order_ref).' Berhasil Dibuat';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Delivery Order '.($orders->order_ref).' Berhasil Dibuat',
            'alert-type' => 'success'
        );
        
        return redirect()->route('delivery.index')->with($notification);
    }

    public function deliveryPrint($id)
    {
        $source = Delivery::find($id);
        $data = Sale::where('order_ref',$source->sales_ref)
                        ->first();
                        
        $details = SaleItem::where('sales_id',$data->id)
                            ->get();
        
        $pdf = PDF::loadview('apps.print.deliveryOrder',compact('source','data','details'));
        return $pdf->download(''.$source->order_ref.'.pdf');                 
    }

    public function deliveryDone(Request $request,$id)
    {
        $data = Delivery::find($id);
        $data->update([
            'status_id' => 'e9395add-e815-4374-8ed3-c0d5f4481ab8',
            'updated_by' => auth()->user()->name,
        ]);

        $source = Delivery::where('id',$id)->first();
        $sales = Sale::where('order_ref',$source->sales_ref)->update([
            'status_id' => 'e9395add-e815-4374-8ed3-c0d5f4481ab8',
        ]);

        $log = 'Delivery Order '.($data->order_ref).' Berhasil Dikirimkan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Delivery Order '.($data->order_ref).' Berhasil Dikirimkan',
            'alert-type' => 'success'
        );
    
        return redirect()->route('delivery.index')->with($notification);
    }

    public function returIndex()
    {
        $sales = Sale::where('sales.status_id','=','458410e7-384d-47bc-bdbe-02115adc4449')
                       ->orWhere('sales.status_id','=','e9395add-e815-4374-8ed3-c0d5f4481ab8')
                       ->orderBy('updated_at','DESC')
                       ->get();
        
        return view('apps.pages.returSales',compact('sales'));
    }

    public function returForm($id)
    {
        $locations = Warehouse::where('id','!=','34437a64-ca03-47ff-be0c-63da5814484e')
                                ->where('id','!=','ce8b061c-b1bb-4627-b80f-6a42a364109b')
                                ->pluck('name','id')->toArray();
        $sales = Sale::join('sale_items','sale_items.sales_id','sales.id')
                       ->where('sales.id',$id)
                       ->get();
        
        return view('apps.input.returSales',compact('sales','locations'))->renderSections()['content'];
    }

    public function returStore(Request $request)
    {
        $this->validate($request, [
            'delivery' => 'required',
            'retur' => 'required',
            'warehouse_id' => 'required',
        ]);
        $lastOrder = ReturSale::count();
        $refs =  'RT/'.str_pad($lastOrder + 1, 4, "0", STR_PAD_LEFT).'/'.'FTI'.'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $sales = $request->sales_id;
        $items = $request->product_id;
        $delivered = $request->deliver;
        $return = $request->retur;
        
        $data = ReturSale::create([
            'sales_id' => $request->input('sales'),
            'warehouse_id' => $request->input('warehouse_id'),
            'created_by' => auth()->user()->name,
        ]);
        $referenceSales = Sale::where('id',$request->input('sales'))->update([
            'status_id' => 'e9f870d8-ebe8-462e-a6b6-c03f4f5bd8eb',
        ]);
        
        foreach($sales as $index=>$sale) {
            $originInventory = Inventory::where('product_id',$items[$index])
                                          ->where('warehouse_id',$request->input('warehouse_id'))
                                          ->orderBy('updated_at','DESC')
                                          ->first();
            $originMovement = InventoryMovement::where('product_id',$items[$index])
                                                 ->where('warehouse_id',$request->input('warehouse_id'))
                                                 ->orderBy('updated_at','DESC')
                                                 ->first();
            $details = ReturItem::create([
                'retur_id' => $data->id,
                'product_id' => $items[$index],
                'quantity' => $return[$index],
            ]);
            if($originInventory == null)
            {
                $dataInventory = Inventory::create([
                    'product_id' => $items[$index],
                    'warehouse_id' => $request->input('warehouse_id'),
                    'min_stock' => '0',
                    'opening_amount' => '0',
                    'closing_amount' => $return[$index],
                ]);
                $dataMovement = InventoryMovement::create([
                    'type' => '8',
                    'inventory_id' => $dataInventory->id,
                    'reference_id' => $refs,
                    'product_id' => $items[$index],
                    'warehouse_id' => $request->input('warehouse_id'),
                    'incoming' => $return[$index],
                    'outgoing' => '0',
                    'remaining' => $return[$index],
                ]);
            } else {
                $dataInventory = Inventory::where('product_id',$items[$index])
                                            ->where('warehouse_id',$request->input('warehouse_id'))
                                            ->update([
                    'closing_amount' => ($originInventory->closing_amount) + ($return[$index]),
                ]);
                
                $dataMovement = InventoryMovement::create([
                    'type' => '8',
                    'inventory_id' => $originInventory->id,
                    'reference_id' => $refs,
                    'product_id' => $items[$index],
                    'warehouse_id' => $request->input('warehouse_id'),
                    'incoming' => $return[$index],
                    'outgoing' => '0',
                    'remaining' => ($originMovement->remaining) + ($return[$index]),
                ]);
            }
        }

        return redirect()->route('retur.index');
    }

}