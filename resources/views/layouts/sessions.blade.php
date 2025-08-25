@if (session('success'))
    <div class="alert alert-success border-left-success fade show" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger border-left-error alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
@endif
