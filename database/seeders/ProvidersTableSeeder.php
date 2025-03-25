<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvidersTableSeeder extends Seeder
{
    public function run()
    {
        $providers = [
            ['name' => 'TEK', 'email' => 'info@tek.es', 'phone' => '+34 911 234 567', 'rating' => 4.8],
            ['name' => 'ELC', 'email' => 'contact@elc.es', 'phone' => '+34 934 567 890', 'rating' => 4.5],
            ['name' => 'COM', 'email' => 'info@com.es', 'phone' => '+34 963 456 789', 'rating' => 3.9],
            ['name' => 'INF', 'email' => 'contact@inf.es', 'phone' => '+34 955 678 901', 'rating' => 4.7],
            ['name' => 'NET', 'email' => 'info@net.es', 'phone' => '+34 944 567 890', 'rating' => 2.8],
            ['name' => 'SYS', 'email' => 'contact@sys.es', 'phone' => '+34 952 345 678', 'rating' => 4.3],
            ['name' => 'DAT', 'email' => 'info@dat.es', 'phone' => '+34 976 789 012', 'rating' => 3.6],
            ['name' => 'SEC', 'email' => 'contact@sec.es', 'phone' => '+34 968 901 234', 'rating' => 4.4],
            ['name' => 'DEV', 'email' => 'info@dev.es', 'phone' => '+34 971 234 567', 'rating' => 2.5],
            ['name' => 'SOL', 'email' => 'contact@sol.es', 'phone' => '+34 928 345 678', 'rating' => 4.6],
            ['name' => 'PRO', 'email' => 'info@pro.es', 'phone' => '+34 965 456 789', 'rating' => 3.8],
            ['name' => 'DIG', 'email' => 'contact@dig.es', 'phone' => '+34 957 567 890', 'rating' => 4.2],
            ['name' => 'TEC', 'email' => 'info@tec.es', 'phone' => '+34 983 678 901', 'rating' => 2.9],
            ['name' => 'SER', 'email' => 'contact@ser.es', 'phone' => '+34 986 789 012', 'rating' => 4.1],
            ['name' => 'INN', 'email' => 'info@inn.es', 'phone' => '+34 958 890 123', 'rating' => 3.7],
            ['name' => 'BIT', 'email' => 'contact@bit.es', 'phone' => '+34 985 901 234', 'rating' => 4.0],
            ['name' => 'LOG', 'email' => 'info@log.es', 'phone' => '+34 945 012 345', 'rating' => 2.7],
            ['name' => 'WEB', 'email' => 'contact@web.es', 'phone' => '+34 981 123 456', 'rating' => 4.3],
            ['name' => 'APP', 'email' => 'info@app.es', 'phone' => '+34 985 234 567', 'rating' => 3.5],
            ['name' => 'HUB', 'email' => 'contact@hub.es', 'phone' => '+34 950 345 678', 'rating' => 4.4]
        ];

        $activeStatusId = DB::table('statuses')->where('name', 'Activo')->first()->id;
        $pendingStatusId = DB::table('statuses')->where('name', 'Pendiente')->first()->id;
        $inactiveStatusId = DB::table('statuses')->where('name', 'Inactivo')->first()->id;

        $madridLocationId = DB::table('locations')->where('name', 'Madrid')->first()->id;
        $barcelonaLocationId = DB::table('locations')->where('name', 'Barcelona')->first()->id;

        foreach ($providers as $index => $provider) {
            // Assign status based on the original data pattern
            $statusId = $activeStatusId; // Default status
            if ($index % 5 == 2) { // Every 3rd provider was pending
                $statusId = $pendingStatusId;
            } elseif ($index % 5 == 4) { // Every 5th provider was inactive
                $statusId = $inactiveStatusId;
            }

            // Alternate between Madrid and Barcelona for locations
            $locationId = $index % 2 == 0 ? $madridLocationId : $barcelonaLocationId;

            Provider::create([
                'name' => $provider['name'],
                'email' => $provider['email'],
                'phone' => $provider['phone'],
                'rating' => $provider['rating'],
                'status_id' => $statusId,
                'location_id' => $locationId
            ]);
        }
    }
}