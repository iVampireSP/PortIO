<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Tunnel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->abortIfNotAdmin($request);
        $t = Tunnel::query()->paginate(50);

        return $this->success($t);
    }

    public function update(Request $request, Tunnel $tunnel): JsonResponse
    {
        $this->abortIfNotAdmin($request);
        $request->validate([
            'locked_reason' => 'nullable|string'
        ]);

        if ($request->get('locked_reason') == "null") {
            $tunnel->update([
                'locked_reason' => null
            ]);
        } else {
            $tunnel->update([
                'locked_reason' => $request->get('locked_reason')
            ]);
        }

        return $this->updated();
    }

    public function destroy(Request $request, Tunnel $tunnel)
    {
        $this->abortIfNotAdmin($request);

        $tunnel->delete();

        return $this->deleted();
    }

    private function abortIfNotAdmin(Request $request): void
    {
        if ($request->bearerToken() == null) {
            abort(403, "Forbidden");
        } else if ($request->bearerToken() != config('review.token')) {
            abort(403, "Forbidden");
        }
    }
}
