<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Species;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(file_exists('./database/schema/species.sql')) {
            $data = file_get_contents('./database/schema/species.sql');
            return DB::unprepared($data);
        } else {
            $file = fopen('./database/schema/reptile_checklist_2020_12.csv',"r");
            $importData_arr = [];
            $i = 0;
            $species = new Species;
            while (($filedata = fgetcsv($file, 10000, ",")) !== FALSE) {
                $num = count($filedata );
                if($i == 0){
                $i++;
                continue;
                }
                for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
                }
                $i++;
            }
            fclose($file);

            foreach($importData_arr as $importData){
                $typeSpecies = false;
                $speciesNumber = intval($importData[6]);
                $changes = " ";

                if(isset($importData[7])) {$changes = strval($importData[7]);}
                if ($importData[0] == "x") {$typeSpecies = true;}

                $species->create([
                    'type_species'  => $typeSpecies,
                    'species'       => $importData[1],
                    'author'        => $importData[2],
                    'subspecies'    => $importData[3],
                    'common_name'   => $importData[4],
                    'higher_taxa'   => $importData[5],
                    'species_number'=> $speciesNumber,
                    'changes'       => $changes
                    ]);
            }
        }
    }
}
