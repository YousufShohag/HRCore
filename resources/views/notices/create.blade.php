@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Notice</h2>

    <form action="{{ route('notices.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Publish Date</label>
            <input type="date" name="publish_date" class="form-control" required>
        </div>
         <div class="form-group">
    <label for="content">Notice Content</label>
    <textarea name="content" id="content" class="form-control">{{ old('content') }}</textarea>
</div>


        <div class="mb-3">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control">
        </div>
{{-- 
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="editor" rows="6" class="form-control"></textarea>
        </div> --}}


       

        <button type="submit" class="btn btn-primary">Save Notice</button>
    </form>
</div>
@endsection

@section('scripts')
<!-- CKEditor 5 Classic build -->
<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error('CKEditor initialization error:', error);
            });
    });
</script>





@endsection
