@extends('layouts.app')

@section('content')
@php
  $user = auth()->user();
  $salesmen = \App\Http\Controllers\LeadController::salesmen();
@endphp

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="mb-1">Lead #{{ $lead->id }} - {{ $lead->name }}</h3>
      <div class="text-muted">
        Phone: {{ $lead->phone ?? '-' }} | Source: {{ $lead->source ?? '-' }}
      </div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary" href="{{ route('leads.index') }}">Back</a>
      <a class="btn btn-primary" href="{{ route('leads.edit',$lead) }}">Edit</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Description</h5>
          <p class="mb-0">{{ $lead->description ?? '—' }}</p>
        </div>
      </div>

      <div class="card shadow-sm mt-3">
        <div class="card-body">
          <h5 class="card-title">Status</h5>
          <span class="badge bg-secondary">{{ strtoupper($lead->status) }}</span>
          <div class="text-muted mt-2">Created: {{ $lead->created_at->format('d M Y, h:i A') }}</div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Assignment</h5>

          <div class="mb-2">
            Current:
            @if($lead->assignee)
              <span class="badge bg-success">{{ $lead->assignee->name }}</span>
            @else
              <span class="badge bg-warning text-dark">Unassigned</span>
            @endif
          </div>

          @if($user->role === 'admin')
            <form method="POST" action="{{ route('leads.assign',$lead) }}" class="mt-3">
              @csrf
              <label class="form-label">Admin Assign to Salesman</label>
              <select name="user_id" class="form-select" required>
                @foreach($salesmen as $s)
                  <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
                @endforeach
              </select>
              <button class="btn btn-success w-100 mt-2">Assign</button>
            </form>
          @endif

          @if(in_array($user->role, ['admin','operation']))
            <form method="POST" action="{{ route('leads.reassign',$lead) }}" class="mt-3">
              @csrf
              <label class="form-label">Operation Re-Assign</label>
              <select name="user_id" class="form-select" required>
                @foreach($salesmen as $s)
                  <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
                @endforeach
              </select>
              <button class="btn btn-warning w-100 mt-2">Reassign</button>
            </form>
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
