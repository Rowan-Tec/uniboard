@extends('layouts.app')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Edit Notice</h4>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('notices.update', $notice) }}">
          @csrf
          @method('PATCH')

          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $notice->title) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              <option value="general" {{ old('category', $notice->category) == 'general' ? 'selected' : '' }}>General</option>
              <option value="events" {{ old('category', $notice->category) == 'events' ? 'selected' : '' }}>Events</option>
              <option value="academic" {{ old('category', $notice->category) == 'academic' ? 'selected' : '' }}>Academic</option>
              <option value="exam" {{ old('category', $notice->category) == 'exam' ? 'selected' : '' }}>Exam</option>
              <option value="scholarship" {{ old('category', $notice->category) == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
              <option value="emergency" {{ old('category', $notice->category) == 'emergency' ? 'selected' : '' }}>Emergency</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select" required>
              <option value="normal" {{ old('priority', $notice->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
              <option value="important" {{ old('priority', $notice->priority) == 'important' ? 'selected' : '' }}>Important</option>
              <option value="emergency" {{ old('priority', $notice->priority) == 'emergency' ? 'selected' : '' }}>Emergency</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content', $notice->content) }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Schedule Publish (optional)</label>
            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $notice->scheduled_at ? $notice->scheduled_at->format('Y-m-d\TH:i') : '') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Expire At (optional)</label>
            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $notice->expires_at ? $notice->expires_at->format('Y-m-d\TH:i') : '') }}">
          </div>

          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
@endsection