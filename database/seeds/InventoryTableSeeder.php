<?php

use Illuminate\Database\Seeder;
use iteos\Models\Inventory;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class InventoryTableSeeder extends SpreadsheetSeeder
{
    public function __construct()
    {
        $this->file = '/dump_db/inventories.xls'; // specify relative to Laravel project base path
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
     
    
    public function run()
    {
        DB::disableQueryLog();
	    parent::run();
    }
}
