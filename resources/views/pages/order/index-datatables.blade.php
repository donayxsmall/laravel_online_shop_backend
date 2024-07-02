@extends('layouts.app')

@section('title', 'Order')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Order</h1>
                <div class="section-header-button">
                    <a href="{{ route('product.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Order</a></div>
                    <div class="breadcrumb-item">All Product</div>
                </div>
            </div>
            <div class="section-body">

                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <h2 class="section-title">Product</h2>
                <p class="section-lead">
                    You can manage all order, such as editing, deleting and more.
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Order</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="table-responsive">
                                    <table id="table-order" class="table table-striped table-bordered" style="width:100%">
                                        <thead style="background-color:aqua;">

                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>

    <script>
        // CREATE DATATABLES
        $(function() {

            var column = [
                {
                    'attr': 'transaction_date',
                    'title' : 'Tanggal Transaksi'
                },
                {
                    'attr': 'transaction_number',
                },
                {
                    'attr': 'total_cost',
                },
                {
                    'attr': 'status',
                },
                {
                    'attr': 'shipping_service',
                },
                {
                    'attr': 'shipping_resi',
                },
                {
                    'attr': 'created_at',
                },
                {
                    'attr': 'updated_at',
                },
                {
                    'attr': 'action'
                }
            ];

            var table = initializeDatatable('table-order', "{{ route('order.index') }}", column);
            initializeDatatableEvents(table, 'table-order');
        });
    </script>
@endpush
