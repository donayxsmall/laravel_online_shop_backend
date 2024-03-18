{{-- @if (Session::has('success')) --}}
{{-- @if (session('success')) --}}
@if (!empty(Session::get('success')))
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>x</span>
            </button>
            {{-- <p>{{ Session::get('success') }}</p> --}}
            <p>{{ session('success') }}</p>
            {{-- {{ Session::forget('success') }} --}}
        </div>
    </div>
@endif
