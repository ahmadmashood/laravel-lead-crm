<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Services\LeadAssigner;

class AutoAssignLeads extends Command
{
  protected $signature = 'leads:auto-assign';
  protected $description = 'Auto assign unassigned leads to online salesmen';
  
public function handle(\App\Services\LeadAssigner $assigner)
{
    $leads = \App\Models\Lead::whereNull('assigned_to')
        ->where('status', 'new')
        ->orderBy('created_at')
        ->limit(100)
        ->get();

    $assigned = 0;

    foreach ($leads as $lead) {
        $u = $assigner->assignNewLeadBalanced($lead);
        if ($u) $assigned++;
    }

    $this->info("Assigned NEW leads: {$assigned}");
    return 0;
}
}
