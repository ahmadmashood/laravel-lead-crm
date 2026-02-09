<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Services\LeadAssigner;
use Illuminate\Http\Request;

class LeadController extends Controller
{
  public function index(Request $req)
  {
    $user = auth()->user();

    $q = Lead::query()->with('assignee');

    if ($user->role === 'salesman') {
      $q->where('assigned_to', $user->id);
    }

    if ($req->filled('status')) $q->where('status', $req->status);
    if ($req->filled('search')) {
      $s = $req->search;
      $q->where(function($x) use ($s){
        $x->where('name','like',"%$s%")
          ->orWhere('phone','like',"%$s%")
          ->orWhere('description','like',"%$s%");
      });
    }

    $leads = $q->latest()->paginate(10)->withQueryString();
    return view('leads.index', compact('leads'));
  }

  public function show(Lead $lead)
  {
    $this->authorizeLead($lead);
    return view('leads.show', compact('lead'));
  }

  public function edit(Lead $lead)
  {
    $this->authorizeLead($lead);
    return view('leads.edit', compact('lead'));
  }

  public function update(Request $req, Lead $lead)
  {
    $this->authorizeLead($lead);

    $data = $req->validate([
      'status' => 'required|in:new,open,won,lost',
      'description' => 'nullable|string',
      'phone' => 'nullable|string',
    ]);

    $lead->update($data);
    return redirect()->route('leads.show', $lead)->with('ok','Updated');
  }

  // Admin assigns manually
  public function assign(Request $req, Lead $lead)
  {
    abort_unless(auth()->user()->role === 'admin', 403);

    $data = $req->validate(['user_id' => 'required|exists:users,id']);
    $lead->update(['assigned_to' => $data['user_id'], 'assigned_at' => now()]);
    return back()->with('ok','Assigned');
  }

  // Operation reassign
  public function reassign(Request $req, Lead $lead)
  {
    abort_unless(in_array(auth()->user()->role, ['admin','operation']), 403);

    $data = $req->validate(['user_id' => 'required|exists:users,id']);
    $lead->update(['assigned_to' => $data['user_id'], 'assigned_at' => now()]);
    return back()->with('ok','Reassigned');
  }

  // Web route to create lead (simulate “new lead from web form”)
 public function store(Request $req, \App\Services\LeadAssigner $assigner)
{
    $data = $req->validate([
        'name' => 'required|string',
        'phone' => 'nullable|string',
        'source' => 'nullable|string',
        'description' => 'nullable|string',
    ]);

    $lead = \App\Models\Lead::create($data + [
        'status' => 'new',
        'created_by' => auth()->id(),
    ]);

    // Balanced auto-assign
    $assigner->assignNewLeadBalanced($lead);

    return redirect()->route('leads.show', $lead);
}

  private function authorizeLead(Lead $lead): void
  {
    $user = auth()->user();
    if ($user->role === 'salesman' && $lead->assigned_to !== $user->id) {
      abort(403);
    }
    // admin/operation can view all
  }

  // dropdown salesmen helper
  public static function salesmen()
  {
    return User::where('role','salesman')->orderBy('name')->get();
  }
}
