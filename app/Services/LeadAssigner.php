<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeadAssigner
{
    public function assignNewLeadBalanced(Lead $lead): ?User
    {
        // Only assign if lead is NEW and unassigned
        if ($lead->status !== 'new' || $lead->assigned_to) {
            return null;
        }

        // Active/online salesmen (last_seen_at within 3 minutes)
        $salesmen = User::query()
            ->where('role', 'salesman')
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', now()->subMinutes(3))
            ->pluck('id');

        if ($salesmen->isEmpty()) return null;

        // Count of NEW leads per salesman
        // pick salesman with 0 new leads first, else least new leads
        $target = DB::table('users')
            ->select('users.id')
            ->leftJoin('leads', function ($join) {
                $join->on('users.id', '=', 'leads.assigned_to')
                     ->where('leads.status', '=', 'new');
            })
            ->whereIn('users.id', $salesmen)
            ->groupBy('users.id')
            ->orderByRaw('COUNT(leads.id) ASC')  // least new leads first
            ->orderBy('users.id', 'ASC')         // tie-breaker stable
            ->first();

        if (!$target) return null;

        $lead->forceFill([
            'assigned_to' => $target->id,
            'assigned_at' => now(),
        ])->save();

        return User::find($target->id);
    }
}
