<?php

namespace App\Http\Controllers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class AuditoriaController extends Controller {
    public function index(): View {
        Gate::authorize('viewAny', \OwenIt\Auditing\Models\Audit::class);

        $auditorias = Audit::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.auditoria.index', compact('auditorias'));
    }
}