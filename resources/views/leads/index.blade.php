@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Leads</h3>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newLeadModal">
      + New Lead (Web)
    </button>
  </div>

  <form class="row g-2 mb-3">
    <div class="col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name/phone/desc">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">All Status</option>
        @foreach(['new','open','won','lost'] as $st)
          <option value="{{$st}}" @selected(request('status')==$st)>{{ strtoupper($st) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary w-100">Filter</button>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th><th>Name</th><th>Phone</th><th>Status</th><th>Assigned</th><th>Created</th><th></th>
          </tr>
        </thead>
        <tbody>
        @foreach($leads as $lead)
          <tr>
            <td>#{{ $lead->id }}</td>
            <td>{{ $lead->name }}</td>
            <td>{{ $lead->phone }}</td>
            <td><span class="badge bg-secondary">{{ strtoupper($lead->status) }}</span></td>
            <td>
              @if($lead->assignee)
                <span class="badge bg-success">{{ $lead->assignee->name }}</span>
              @else
                <span class="badge bg-warning text-dark">Unassigned</span>
              @endif
            </td>
            <td>{{ $lead->created_at->format('d M Y, h:i A') }}</td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('leads.show',$lead) }}">Open</a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $leads->links() }}</div>
</div>

<!-- New Lead Modal -->
<div class="modal fade" id="newLeadModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('leads.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Create Lead (Web)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input name="phone" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Source</label>
          <input name="source" class="form-control" placeholder="web/facebook/walkin">
        </div>
        <div class="col-md-8">
          <label class="form-label">Description</label>
          <input name="description" class="form-control" placeholder="eg: car loan / insurance...">
        </div>
        <div class="col-12">
          <div class="alert alert-info mb-0">
            Note: Save hote hi auto-assign service online salesman ko assign karegi.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary">Create</button>
      </div>
    </form>
  </div>
</div>
@endsection
