@extends('layouts.app')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">My Trashed Notices</h4>

    @if($trashedNotices->isEmpty())
      <div class="card">
        <div class="card-body text-center py-6">
          <p class="mb-0">Your trash bin is empty.</p>
        </div>
      </div>
    @else
      <div class="row">
        @foreach($trashedNotices as $notice)
          <div class="col-md-6 mb-4">
            <div class="card h-100 border-warning">
              <div class="card-body">
                <h5 class="card-title text-warning">{{ $notice->title }}</h5>
                <p class="text-muted small mb-3">
                  Trashed {{ $notice->deleted_at->diffForHumans() }}
                </p>
                <p>{{ Str::limit($notice->content, 150) }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <a href="{{ route('notices.index') }}" class="btn btn-outline-primary mt-4">
      Back to Notices
    </a>
  </div>
@endsection