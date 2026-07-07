<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinicas', function (Blueprint $table): void {
            $table->boolean('is_shared')->default(false)->after('nombre')->index();
        });

        $sharedClinicId = DB::table('clinicas')->insertGetId([
            'nombre' => 'Espacio compartido',
            'is_shared' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $activeUsers = DB::table('users')
            ->whereIn('subscription_status', ['active', 'trialing'])
            ->orderBy('id')
            ->get();
        $claimedClinics = [];

        foreach ($activeUsers as $user) {
            $clinicId = $user->clinica_id;

            if (! $clinicId || in_array($clinicId, $claimedClinics, true)) {
                $clinicId = DB::table('clinicas')->insertGetId([
                    'nombre' => 'Clínica de '.$user->name,
                    'is_shared' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $claimedClinics[] = $clinicId;

            DB::table('clinicas')->where('id', $clinicId)->update(['is_shared' => false]);
            DB::table('users')->where('id', $user->id)->update([
                'clinica_id' => $clinicId,
                'clinica_rol' => 'propietario',
            ]);
        }

        DB::table('users')
            ->where(function ($query): void {
                $query
                    ->whereNull('subscription_status')
                    ->orWhereNotIn('subscription_status', ['active', 'trialing']);
            })
            ->where(function ($query) use ($claimedClinics): void {
                $query->where('clinica_rol', 'propietario');

                if ($claimedClinics !== []) {
                    $query->orWhereNotIn('clinica_id', $claimedClinics);
                }
            })
            ->update([
                'clinica_id' => $sharedClinicId,
                'clinica_rol' => 'usuario',
            ]);
    }

    public function down(): void
    {
        Schema::table('clinicas', function (Blueprint $table): void {
            $table->dropColumn('is_shared');
        });
    }
};
