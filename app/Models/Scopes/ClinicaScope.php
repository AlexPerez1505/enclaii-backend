<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ClinicaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $clinicaId = Auth::user()?->clinica_id;

        if ($clinicaId) {
            $builder->where($model->qualifyColumn('clinica_id'), $clinicaId);
        }
    }
}
