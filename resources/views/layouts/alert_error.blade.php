@if ($errors->any() || session('error'))
    <div class="alert alert-danger">

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if (session('error'))
            {{ session('error') }}
        @endif
    </div>
@endif
