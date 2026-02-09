@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Edit Lead #{{ $lead->id }}</h3>
    <a class="btn btn-outline-secondary" href="{{ route('leads.show',$lead) }}">Back</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('leads.update',$lead) }}">
        @csrf @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="{{ old('phone',$lead->phone) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              @foreach(['new','open','won','lost'] as $st)
                <option value="{{$st}}" @selected(old('status',$lead->status)==$st)>{{ strtoupper($st) }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description',$lead->description) }}</textarea>
          </div>
        </div>

        <button class="btn btn-primary mt-3">Save Changes</button>
      </form>
    </div>
  </div>
</div>
@endsection
