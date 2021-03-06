<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Product;
use iteos\Models\ProductBom;
use iteos\Models\Inventory;
use iteos\Models\InventoryMovement;
use iteos\Models\Warehouse;
use iteos\Models\InternalTransfer;
use iteos\Models\InternalItems;
use iteos\Models\Manufacture;
use iteos\Models\ManufactureItem;
use iteos\Models\WorkItem;
use iteos\Models\UomValue;
use iteos\Models\Sale;
use iteos\Models\Reference;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Auth;

class ManufactureManagementController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:Can Access Manufactures');
         $this->middleware('permission:Can Create Manufacture', ['only' => ['create','store']]);
         $this->middleware('permission:Can Edit Manufacture', ['only' => ['edit','update']]);
         $this->middleware('permission:Can Delete Manufacture', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = Manufacture::where('status_id','!=','5bc79891-e396-4792-a0f3-617ece2a00ce')->get();
       
        return view ('apps.pages.manufacture',compact('data'));
    }

    public function requestIndex()
    {
        $data = Manufacture::where('status_id','5bc79891-e396-4792-a0f3-617ece2a00ce')->get();
        
        return view('apps.pages.manufactureRequest',compact('data'));
    }

    public function manufactureProduct(Request $request)
    {
        $search = $request->get('product');
        $result = Product::where('is_manufacture','1')
                            ->where('name','LIKE','%'.$search. '%')
                            ->where('is_manufacture','1')
                            ->select('id','name')
                            ->get();

        return response()->json($result);
    }

    public function createRequest()
    {
        $orders  = Sale::where('status_id','8083f49e-f0aa-4094-894f-f64cd2e9e4e9')->pluck('order_ref','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        $products = Product::where('is_manufacture','1')
                            ->get();

        return view('apps.input.manufactureRequest',compact('orders','uoms','products'));
    }

    public function storeRequest(Request $request)
    {
        $latestOrder = Reference::where('type','5')->count();
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $ref = 'MR/FTI/'.str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $bases = UomValue::where('id',$request->input('uom_id'))->first();
        if($bases->is_parent == null) {
            $convertion = ($request->input('quantity')) * ($bases->value); 
        } else {
            $convertion = $request->input('quantity');
        }
        $input = [
            'order_ref' => $ref, 
            'sales_order' => $request->input('sales_order'),
            'product_name' => $request->input('product'),
            'deadline' => $request->input('deadline'),
            'status_id' => '5bc79891-e396-4792-a0f3-617ece2a00ce',
            'warehouse_id' => 'ce8b061c-b1bb-4627-b80f-6a42a364109b',
            'man_plan' => $convertion,
            'created_by' => auth()->user()->name,
        ];
        $refs = Reference::create([
            'type' => '5',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $ref,
        ]);
        $names = Product::join('product_boms','product_boms.product_id','products.id')
                          ->where('products.name',$request->input('product'))
                          ->orWhere('products.product_barcode',$request->input('product'))
                          ->get();
        
        $data = Manufacture::create($input);
        foreach($names as $index=>$name) {
            $details = ManufactureItem::create([
                'manufacture_id' => $data->id,
                'item_name' => $names[$index]->material_name,
                'qty' => ($names[$index]->quantity) * ($data->man_plan),
                'uom_id' => $names[$index]->uom_id,
            ]);
        }
        
        $log = 'Manufacture Request '.($data->order_ref).' Berhasil Dibuat';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Manufacture Request '.($data->order_ref).' Berhasil Dibuat',
            'alert-type' => 'success'
        );

        return redirect()->route('manufacture-request.index')->with($notification);
    }

    public function approveRequest($id)
    {
        $latestOrder = Reference::where('type','6')->count();
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $ref = 'MO/FTI/'.str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $data = Manufacture::find($id);
        $accept = $data->update([
            'order_ref' => $ref,
            'status_id' => '5af2f030-efe0-426e-819d-6df5f6fb8cc5',
            'approve_by' => auth()->user()->name,
        ]);
        $refs = Reference::create([
            'type' => '6',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $ref,
        ]);
        $log = 'Manufacture Request '.($data->order_ref).' Berhasil Disetujui';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Manufacture Request '.($data->order_ref).' Berhasil Disetujui, JANGAN LUPA melakukan transfer stock ke gudang Manufaktur',
            'alert-type' => 'success'
        );

        return redirect()->route('manufacture.index')->with($notification);
    }

    public function checkStock($id)
    {
        $data = ManufactureItem::join('inventories','inventories.product_name','=','manufacture_items.item_name')
                                ->where('manufacture_items.manufacture_id',$id)
                                ->get();
       
        return view('apps.show.manufactureStock',compact('data'))->renderSections()['content'];
    }

    public function reCheckStock($id)
    {
        $data = ManufactureItem::join('inventories','inventories.product_name','=','manufacture_items.item_name')
                                ->where('manufacture_items.manufacture_id',$id)
                                ->where('inventories.warehouse_name','Gudang Produksi')
                                ->get();
        $getID = Manufacture::find($id);
        return view('apps.show.manufactureStock',compact('data','getID'))->renderSections()['content'];
    }

    public function approveStock(Request $request,$id)
    {
        $data = Manufacture::find($id);
        $data->update([
            'status_id' => 'd4f7f9f3-4f5f-4063-b6ab-dc03f89ec87e',
            'updated_by' => auth()->user()->name,
        ]);

        return redirect()->back();
    }

    public function makeManufacture(Request $request,$id)
    {
        $data = Manufacture::find($id);
        $workItems = ManufactureItem::join('products','products.name','manufacture_items.item_name')
                        ->where('manufacture_items.manufacture_id',$id)
                        ->get();

        $updateStatus = $data->update([
            'status_id' => 'c2fdba02-e765-4ee8-8c8c-3073209ddd26',
            'process_by' => auth()->user()->name,
            'start_production' => Carbon::now(),
        ]);
        $log = 'Manufacture Order '.($data->order_ref).' Berhasil Dijalankan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Manufacture Order '.($data->order_ref).' Berhasil Dijalankan',
            'alert-type' => 'success'
        );
        return redirect()->route('manufacture.index')->with($notification);
    }

    public function manufactureShow($id)
    {
        $data = Manufacture::find($id);
        $details = ManufactureItem::where('manufacture_id',$id)->get();
        
        return view('apps.show.manufactureOrderNew',compact('data','details'));
    }

    public function manufactureDone($id)
    {
        $products = Manufacture::where('id',$id)->get();
        $data = Manufacture::join('manufacture_items','manufacture_items.manufacture_id','manufactures.id')
                            ->where('manufactures.id',$id)
                            ->groupBy('manufacture_items.item_name','manufacture_items.qty')
                            ->select('manufacture_items.item_name','manufacture_items.qty')
                            ->get();
        return view('apps.input.manufactureDone',compact('data','products'))->renderSections()['content'];
    }

    public function process(Request $request)
    {
        $orders = Manufacture::join('manufacture_items','manufacture_items.manufacture_id','manufactures.id')->where('manufacture_items.manufacture_id',$request->input('id'))->first();
        $itemInventory = Inventory::where('product_name',$request->input('product_name'))->where('warehouse_name','Gudang Produksi')->first();
        $items = $request->material_name;
        $usage = $request->usage;
        $scrap = $request->scrap;
        /*Input Production Result*/
        $updateQty = ManufactureItem::where('id',$request->input('id'))->update(['result'=>$request->input('result')]);
        /*Create or Update Inventory Production*/
        if($itemInventory == null) {
            $refProduct = Product::where('name',$request->input('product_name'))->first();
            $finishItems = Inventory::create([
                'product_id' => $refProduct->id,
                'product_name' => $request->input('product_name'),
                'warehouse_name' => 'Gudang Produksi',
                'min_stock' => '0',
                'opening_amount' => $request->input('result'),
                'closing_amount' => $request->input('result'),
            ]);
        } else {
            $finishItems = Inventory::where('product_name',$request->input('product_name'))->where('warehouse_name','Gudang Produksi')->update([
                'opening_amount' => $itemInventory->opening_amount,
                'closing_amount' => ($itemInventory->closing_amount) + ($request->input('result')),
            ]);
        }
        $idFinish = Inventory::where('product_name',$request->input('product_name'))->where('warehouse_name','Gudang Produksi')->first();
        $lastMoves = InventoryMovement::where('product_name',$request->input('product_name'))->where('warehouse_name','Gudang Produksi')->orderBy('updated_at','DESC')->first();
        /*Register Inventory Movement on Production Result*/
        if($lastMoves == null) {
            $finishIn = InventoryMovement::create([
                'type' => '7',
                'inventory_id' => $idFinish->id,
                'reference_id' => $orders->order_ref,
                'product_name' => $idFinish->product_name,
                'warehouse_name' => 'Gudang Produksi',
                'incoming' => $request->input('result'),
                'outgoing' => '0',
                'remaining' => $request->input('result'),
            ]);
        } else {
            $finishIn = InventoryMovement::create([
                'type' => '7',
                'inventory_id' => $idFinish->id,
                'reference_id' => $orders->order_ref,
                'product_name' => $idFinish->product_name,
                'warehouse_name' => 'Gudang Produksi',
                'incoming' => $request->input('result'),
                'outgoing' => '0',
                'remaining' => ($lastMoves->remaining) + ($request->input('result')),
            ]);
        }
        /* Register Inventory Movement on Production Material*/       
        foreach($items as $index=>$item) {
            $refProduct = Product::where('name',$item)->first();
            $scrapInventory = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Scrap')->first();
            $rawInventory = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Produksi')->first();
            $startWh = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->first();
            /*Create or Update Scrap Inventory*/
            if($scrapInventory == null) {
                $scrapItems = Inventory::create([
                    'product_id' => $refProduct->id,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Scrap',
                    'min_stock' => '0',
                    'opening_amount' => $scrap[$index],
                    'closing_amount' => $scrap[$index],
                ]);
                $outStart = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->update([
                    'closing_amount' => ($startWh->closing_amount) - ($scrap[$index]),
                ]);
            } else {
                $scrapItems = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Scrap')->update([
                    'closing_amount' => ($scrapInventory->closing_amount) + $scrap[$index],
                ]);
                $outStart = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->update([
                    'closing_amount' => ($startWh->closing_amount) - ($scrap[$index]),
                ]);
            }
            /*Create or Update Raw Production Inventory*/
            if($rawInventory == null) {
                $rawItems = Inventory::create([
                    'product_id' => $refProduct->id,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Produksi',
                    'min_stock' => '0',
                    'opening_amount' => $usage[$index],
                    'closing_amount' => $usage[$index],
                ]);
                $outStart = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->update([
                    'closing_amount' => ($startWh->closing_amount) - ($usage[$index]),
                ]);
            } else {
                $rawItems = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Produksi')->update([
                    'closing_amount' => ($rawInventory->closing_amount) + $usage[$index],
                ]);
                $outStart = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->update([
                    'closing_amount' => ($startWh->closing_amount) - ($usage[$index]),
                ]);
            }
            $idScrap = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Scrap')->first();
            $lastMove = InventoryMovement::where('product_name',$item)->where('warehouse_name','Gudang Scrap')->orderBy('updated_at','DESC')->first();
            /*Create Scrap Inventory Movement*/
            if($lastMove == null) {
                $scrapIn = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idScrap->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Scrap',
                    'incoming' => $scrap[$index],
                    'outgoing' => '0',
                    'remaining' => $scrap[$index],
                ]);
            } else {
                $scrapIn = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idScrap->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Scrap',
                    'incoming' => $scrap[$index],
                    'outgoing' => '0',
                    'remaining' => ($lastMove->remaining) + ($scrap[$index]),
                ]);
            }
            $idUsage = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->first();
            $startMove = InventoryMovement::where('product_name',$item)->where('warehouse_name','Gudang Utama')->orderBy('updated_at','DESC')->first();
            $usageMove = InventoryMovement::where('product_name',$item)->where('warehouse_name','Gudang Produksi')->orderBy('updated_at','DESC')->first();
            
                if($usageMove == null) {
                    $startUsage = InventoryMovement::create([
                        'type' => '7',
                        'inventory_id' => $idUsage->id,
                        'reference_id' => $orders->order_ref,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Utama',
                        'incoming' => '0',
                        'outgoing' => $usage[$index],
                        'remaining' => ($idUsage->closing_amount) - ($usage[$index]),
                    ]);
                    $usageIn = InventoryMovement::create([
                        'type' => '7',
                        'inventory_id' => $idUsage->id,
                        'reference_id' => $orders->order_ref,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Produksi',
                        'incoming' => $usage[$index],
                        'outgoing' => '0',
                        'remaining' => $usage[$index],
                    ]);
                    $usageOut = InventoryMovement::create([
                        'type' => '7',
                        'inventory_id' => $idUsage->id,
                        'reference_id' => $orders->order_ref,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Produksi',
                        'incoming' => '0',
                        'outgoing' => $usage[$index],
                        'remaining' => ($usageIn->remaining) - ($usage[$index]),
                    ]);
                } else {
                    $startUsage = InventoryMovement::create([
                        'type' => '7',
                        'inventory_id' => $idUsage->id,
                        'reference_id' => $orders->order_ref,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Utama',
                        'incoming' => '0',
                        'outgoing' => $usage[$index],
                        'remaining' => ($idUsage->closing_amount) - ($usage[$index]),
                    ]);
                    $usageOut = InventoryMovement::create([
                        'type' => '7',
                        'inventory_id' => $idUsage->id,
                        'reference_id' => $orders->order_ref,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Produksi',
                        'incoming' => '0',
                        'outgoing' => $usage[$index],
                        'remaining' => ($usageMove->remaining) - ($usage[$index]),
                    ]);
                }    
            
            /*if($usageMove == null) {
                $startUsage = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idUsage->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Utama',
                    'incoming' => '0',
                    'outgoing' => $usage[$index],
                    'remaining' => ,
                ]);
                $usageIn = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idUsage->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Produksi',
                    'incoming' => $usage[$index],
                    'outgoing' => '0',
                    'remaining' => $usage[$index],
                ]);
                $usageOut = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idUsage->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Produksi',
                    'incoming' => '0',
                    'outgoing' => $usage[$index],
                    'remaining' => ($usageIn->remaining) - ($usage[$index]),
                ]);
                /*$scrapOut = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idUsage->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Produksi',
                    'incoming' => '0',
                    'outgoing' => $scrap[$index],
                    'remaining' => ($usageOut->remaining) - ($scrap[$index]),
                ]);
            } else {
                $usageOut = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idUsage->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Produksi',
                    'incoming' => '0',
                    'outgoing' => $usage[$index],
                    'remaining' => ($usageMove->remaining) - ($usage[$index]),
                ]);
                /*$scrapOut = InventoryMovement::create([
                    'type' => '7',
                    'inventory_id' => $idUsage->id,
                    'reference_id' => $orders->order_ref,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Produksi',
                    'incoming' => '0',
                    'outgoing' => $scrap[$index],
                    'remaining' => ($usageOut->remaining) - ($scrap[$index]),
                ]);
            }*/
            
            $updateInventory = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Produksi')->first();
            $final = $updateInventory->update([
                'closing_amount' => ($updateInventory->closing_amount) - (($usage[$index])+($scrap[$index])),
            ]);
        }
        
        $data = Manufacture::join('manufacture_items','manufacture_items.manufacture_id','manufactures.id')->where('manufacture_items.manufacture_id',$request->input('id'))->first();
        $updates = Manufacture::where('id',$data->manufacture_id)->update([
            'manufactures.status_id' => '0fb7f4e6-e293-429d-8761-f978dc850a97',
            'manufactures.man_result' => $request->input('result'),
            'manufactures.end_by' => auth()->user()->name,
            'manufactures.end_production' => Carbon::now(),
        ]);
        return redirect()->route('manufacture.index');
    }
}
