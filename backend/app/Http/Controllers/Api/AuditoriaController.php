<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller {
    public function index(): JsonResponse {
        Gate::authorize('viewAny', Audit::class);

        return response()->json(
            Audit::with('user')
                ->latest()
                ->paginate(20)
        );
    }
}
