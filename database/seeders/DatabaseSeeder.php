<?php

namespace Database\Seeders;

use App\Models\configuracion;
use App\Models\correlativo;
use App\Models\impuesto;
use App\Models\role;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Configuracion::insert([
            ['codigo' => 'PRFAC', 'detalle' => 'Imprimir Factura', 'valor' => 'S', 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'PRFCO', 'detalle' => 'Imprimir Copia', 'valor' => 'N', 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'SCANB', 'detalle' => 'Usar Lector de Código de Barras', 'valor' => 'N', 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'TAMAN', 'detalle' => 'Tamaño de Impresión', 'valor' => 'CARTA', 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'TMREC', 'detalle' => 'Tamaño del Recibo', 'valor' => 'CARTA', 'created_at' => now(), 'updated_at' => now()],
        ]);


        $impuesto0 = new impuesto();
        $impuesto0->porcentaje = 0;
        $impuesto0->save();

        $impuesto15 = new impuesto();
        $impuesto15->porcentaje = 15;
        $impuesto15->save();

        $impuesto18 = new impuesto();
        $impuesto18->porcentaje = 18;
        $impuesto18->save();

        $correlativo = new correlativo();
        $correlativo->codigo = "ROLE";
        $correlativo->description = "Correlativo de Roles";
        $correlativo->last = 1;
        $correlativo->created_at = Carbon::now();
        $correlativo->updated_at = Carbon::now();
        $correlativo->save();

        $role = new role();
        $role->codigo = "ROL000001";
        $role->rol = 'MEGA ADMINISTRADOR';
        $role->ver_informacion = "S";
        $role->guardar = 'S';
        $role->actualizar = 'S';
        $role->eliminar = 'S';
        $role->imprimir = 'S';
        $role->reimprimir = 'S';
        $role->finanzas = 'S';
        $role->save();

        $user = new User();
        $user->username = 'admin';
        $user->name = 'Administrador';
        $user->email = 'carloseeduardmaldonado@gmail.com';
        $user->password = bcrypt('admin');
        $user->remember_token = Str::random(10);
        $user->profile_photo_path = null;
        $user->idrol = 'ROL000001';
        $user->save();
    }
}
