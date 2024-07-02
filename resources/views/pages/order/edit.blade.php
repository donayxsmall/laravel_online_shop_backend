@extends('layouts.app')

@section('title', 'Edit Order Forms')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush


@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Update Order Forms</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href={{ route('order.index') }}>Order</a></div>
                    <div class="breadcrumb-item">Order Forms</div>
                </div>
            </div>

            <div class="section-body">

                {{-- <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div> --}}

                <h2 class="section-title">Update Order Forms</h2>
                <p class="section-lead">Update Order</p>

                {{-- Alert error --}}
                <div class="col-12">
                    @include('layouts.alert_error')
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form method="POST" action="{{ route('order.update', $order) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-header">
                                    <h4>Update Order</h4>
                                </div>
                                <div class="card-body">

                                    <x-text-field
                                        label="Transaction Number"
                                        name="transaction_number"
                                        value="{{ $order->transaction_number }}"
                                        readonly
                                    />

                                    <x-text-field
                                        label="Total Cost"
                                        name="total_cost"
                                        value="{{ $order->total_cost }}"
                                        {{-- value="{{ old('total_cost', $order->total_cost) }}" --}}
                                        readonly
                                        formatNumber
                                    />

                                        <div>
                                            <h8 style="color:red;">Detail Produk <span style="font-size: 20px;" id="toggle-button" class="toggle-button" onclick="toggleDetails()">+</span></h8>
                                            <hr>

                                            <div class="table-responsive">
                                                <table id="product-details-table" class="table table-striped table-bordered" style="width:100%;text-align: center;text-valign:center;">
                                                    <thead style="background-color:aqua;">
                                                        <tr>
                                                            <th>Image</th>
                                                            <th>Info Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Total Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->orderItems as $item )

                                                        <tr>
                                                            <td>
                                                                <img src="<?=asset('storage/' . $item->product->image) ?>" width="100" height="100" style="padding:10px;" class="gallery-item circular-box"  />
                                                            </td>
                                                            <td>{{$item->product->name}}</td>
                                                            <td>{{$item->quantity}}</td>
                                                            <td>Rp{{number_format($item->product->price)}}</td>
                                                            <td>Rp{{number_format($item->product->price * $item->quantity)}}</td>
                                                        </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td colspan="4" style="text-align: right;"><b>Total Barang</b></td>
                                                            <td><b>Rp{{number_format($order->subtotal)}}</b></td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="4" style="text-align: right;"><b>Biaya Kirim</b></td>
                                                            <td><b>Rp{{number_format($order['shipping_cost'])}}</b></td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="4" style="text-align: right;"><b>Biaya Layanan</b></td>
                                                            <td><b>Rp{{number_format($order['bank_fee'])}}</b></td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="4" style="text-align: right;"><b>Total Biaya</b></td>
                                                            <td><b>Rp{{number_format($order['total_cost'])}}</b></td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    <x-dropdown-field
                                        label="Status"
                                        name="status"
                                        selected="{{ old('status', $order->status) }}"
                                        :items="$listStatus"
                                        required
                                    />

                                    <x-text-field
                                        label="Shipping Courier"
                                        name="shipping_service"
                                        value="{{ $order->shipping_service }}"
                                        {{-- readonly --}}
                                    />

                                    <x-text-field
                                        label="Nomor Resi"
                                        name="shipping_resi"
                                        value="{{ $order->shipping_resi }}"
                                    />




                                    {{-- <div class="form-group">
                                        <label for="your_textarea">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" style="height: 100px;">{{ old('description', $product->description) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text"
                                            class="form-control @error('price')
                                            is-invalid
                                        @enderror"
                                            name="price" value="{{ old('price', $product->price) }}">

                                        @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Stock</label>
                                        <input type="text"
                                            class="form-control @error('stock')
                                            is-invalid
                                        @enderror"
                                            name="stock" value="{{ old('stock', $product->stock) }}">

                                        @error('stock')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                            name="category_id">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="form-group">
                                        <label>Photo</label>

                                        <input type="file" class="form-control" name="image" accept="image/*"
                                            @error('image') is-invalid @enderror id="selectImage">

                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <img id="preview" src="{{ asset('storage/' . $product->image) }}"
                                            alt="your image" class="mt-3" style="max-width: 100%; max-height: 300px;" />
                                    </div> --}}

                                    {{-- <div class="form-group">
                                        <label for="image">Photo</label>
                                        <div id="image-preview" class="image-preview"
                                            style="background-repeat: no-repeat; background-size: cover; background-image: url('{{ asset(Storage::url($product->image)) }}')">
                                            <label for="image-upload" id="image-label">Choose File</label>
                                            <input type="file" name="image" id="image-upload">
                                        </div>

                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/cleave.js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('library/cleave.js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('library/upload-preview/upload-preview.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/forms-advanced-forms.js') }}"></script>


    <script>
        // selectImage.onchange = evt => {
        //     preview = document.getElementById('preview');
        //     preview.style.display = 'block';
        //     const [file] = selectImage.files
        //     if (file) {
        //         preview.src = URL.createObjectURL(file)
        //     }
        // }

        $.uploadPreview({
            input_field: "#image-upload", // Default: .image-upload
            preview_box: "#image-preview", // Default: .image-preview
            label_field: "#image-label", // Default: .image-label
            label_default: "Choose File", // Default: Choose File
            label_selected: "Change File", // Default: Change File
            no_label: false, // Default: false
            success_callback: null // Default: null
        });
    </script>
@endpush
