<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Dictionary\Consumable\Consumable;
use App\Models\Dictionary\Consumable\ConsumableTypesEnum;
use App\Models\Dictionary\Printer\Printer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DictionarySeeder extends Seeder
{

    private function getIdAuthor()
    {        
        return User::first()->id;
    }

    private function getDbNowDate()
    {
        return DB::raw('NOW()');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idAuthor = $this->getIdAuthor();
        $dbNow = $this->getDbNowDate();
        
        // printers
        Printer::insert([
            [
                'vendor' => 'Xerox',
                'model' => 'VersaLink C7000',
                'is_color_print' => 1,
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
            [
                'vendor' => 'HP',
                'model' => 'Color Laser 150nw',
                'is_color_print' => 0,
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
            [
                'vendor' => 'Epson',
                'model' => 'L130',
                'is_color_print' => 1,
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
        ]);
        $printerXerox = Printer::firstWhere('vendor', 'Xerox');
        $printerHP = Printer::firstWhere('vendor', 'HP');

        // consumables
        Consumable::insert([
            [
                'type' => 'cartridge',
                'name' => '106R03765',
                'color' => 'black',
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
            [
                'type' => 'cartridge',
                'name' => '106R03767',
                'color' => 'magenta',
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
            [
                'type' => 'other',                
                'name' => '113R00782',
                'color' => null,
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
            [
                'type' => 'cartridge',
                'name' => 'W2070A',
                'color' => 'black',
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],

            [
                'type' => 'cartridge',
                'name' => 'Kyocera TK-8360C',
                'color' => 'black',
                'id_author' => $idAuthor,
                'created_at' => $dbNow,
                'updated_at' => $dbNow,
            ],
        ]);

        
        $consumableXerox1 = Consumable::firstWhere('name', '106R03765');
        $consumableXerox2 = Consumable::firstWhere('name', '106R03767');
        $consumableXerox3 = Consumable::firstWhere('name', '113R00782');
        $consumableHP = Consumable::firstWhere('name', 'W2070A');        
        
        $printerXerox->consumables()->attach($consumableXerox1->id, ['id_author' => $idAuthor]);
        $printerXerox->consumables()->attach($consumableXerox2->id, ['id_author' => $idAuthor]);
        $printerXerox->consumables()->attach($consumableXerox3->id, ['id_author' => $idAuthor]);

        $printerHP->consumables()->attach($consumableHP->id, ['id_author' => $idAuthor]);

    }
}
