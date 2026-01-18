@extends('backEnd.layouts.master')
@section('title',$order_status->name.' Order')
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('admin.order.create')}}" class="btn btn-danger rounded-pill"><i class="fe-shopping-cart"></i> Add New</a>
                </div>
                <h4 class="page-title">{{$order_status->name}} Order ({{$order_status->orders_count}})</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row order_page">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <ul class="action2-btn">
                                <li><a data-bs-toggle="modal" data-bs-target="#asignUser" class="btn rounded-pill btn-success"><i class="fe-plus"></i> Assign User</a></li>
                                <li><a data-bs-toggle="modal" data-bs-target="#changeStatus" class="btn rounded-pill btn-primary"><i class="fe-plus"></i> Change Status</a></li>
                                <li><a href="{{route('admin.order.bulk_destroy')}}" class="btn rounded-pill btn-danger order_delete"><i class="fe-plus"></i> Delete All</a></li>
                                <li><a href="{{route('admin.order.order_print')}}" class="btn rounded-pill btn-info multi_order_print"><i class="fe-printer"></i> Print</a></li>
                                {{--@if($steadfast)
                                <li><a href="{{route('admin.bulk_courier', 'steadfast')}}" class="btn rounded-pill btn-warning multi_order_courier"><i class="fe-truck"></i> Courier</a></li>
                                @endif--}}
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <form class="custom_form">
                                <div class="form-group">
                                    <input type="text" name="keyword" placeholder="Search">
                                    <button class="btn  rounded-pill btn-info">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive ">
                        <table id="datatable-buttons" class="table table-striped   w-100">
                            <thead>
                                <tr>
                                    <th style="width:2%">
                                        <div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input checkall" value=""></label>
                                    <th style="width:2%">SL</th>
                    </div>
                    </th>
                    <th style="width:8%">Action</th>
                    <th style="width:8%">Invoice</th>
                    <th style="width:8%">Ip Address</th>
                    <th style="width:10%">Date</th>
                    <th style="width:10%">Name</th>
                    <th style="width:10%">Phone</th>
                    <th style="width:10%">Assign</th>
                    <th style="width:10%">Amount</th>
                    <th style="width:10%">Trak Order  </th>
                    <th style="width:10%">Status</th>
                    <th> Froud Checker </th>
                    </tr>
                    </thead>


                    <tbody>
                        @foreach($show_data as $key=>$value)
                        
                        <tr>
                            <td><input type="checkbox" class="checkbox" value="{{$value->id}}"></td>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                <div class="button-list custom-btn-list">
                                    <a href="{{route('admin.order.invoice',['invoice_id'=>$value->invoice_id])}}" title="Invoice"><i class="fe-eye"></i></a>
                                    <a href="{{route('admin.order.process',['invoice_id'=>$value->invoice_id])}}" title="Process"><i class="fe-settings"></i></a>
                                    <a href="{{route('admin.order.edit',['invoice_id'=>$value->invoice_id])}}" title="Edit"><i class="fe-edit"></i></a>
                                    <form method="post" action="{{route('admin.order.destroy')}}" class="d-inline">
                                        @csrf
                                        <input type="hidden" value="{{$value->id}}" name="id">
                                        <button type="submit" title="Delete" class="delete-confirm"><i class="fe-trash-2"></i></button>
                                    </form>
                                    <a data-bs-toggle="modal" data-bs-target="#pathao{{$value->id}}" class="btn btn-success">pathao</a>
                                        <div class="container mb-5">
                                      
                                    </div>
                                </div>
                            </td>

                            <td>{{$value->invoice_id}}</td>
                            
                            <td>{{$value->ip_address}}</td>
                            <td>{{date('d-m-Y', strtotime($value->updated_at))}}<br> {{date('h:i:s a', strtotime($value->updated_at))}}</td>
                            <td><strong>{{$value->shipping?$value->shipping->name:''}}</strong>
                                <p>{{$value->shipping?$value->shipping->address:''}}</p>
                            </td>
                            <td>{{$value->shipping?$value->shipping->phone:''}}</td>
                            <td>{{$value->user?$value->user->name:''}}</td>
                            <td>৳{{$value->amount}}</td>
                            <td> 
                             @if($value->courier == 'pathao' && $value->status->name =='Shipped')
                                <a href="https://merchant.pathao.com/tracking?consignment_id={{ $value->tracking_id }}&phone={{ $value->shipping->phone }}" 
                                target="_blank" 
                                class="btn btn-sm btn-primary">
                                Track Order
                                </a>
                            @endif
                            </td>
                            
                            </td>
                            <td>{{$value->status?$value->status->name:''}}</td>
                            <td>
                                <button class="fraud-checker btn btn-info" data-phone="{{$value->shipping->phone}}">Check Fraud</button>

                                <!-- Modal HTML (hidden by default) -->
                                <div id="fraudModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background: rgba(0,0,0,0.6);
    z-index:9999; justify-content:center; align-items:center;">
                                    <div style="background:#fff; border-radius:8px; max-width:600px; width:90%; padding:20px; position:relative; box-shadow:0 4px 15px rgba(0,0,0,0.3);">
                                        <button id="fraudModalClose" style="position:absolute; top:12px; right:12px; background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
                                        <h2 style="margin-top:0; color:#6a0dad;">Fraud Check Result</h2>
                                        <div id="fraudModalContent" style="max-height:400px; overflow-y:auto; font-family: Arial, sans-serif;"></div>
                                    </div>
                                </div>

                                <style>
                                    #fraudModal table {
                                        width: 100%;
                                        border-collapse: collapse;
                                    }

                                    #fraudModal th,
                                    #fraudModal td {
                                        border: 1px solid #ddd;
                                        padding: 8px 12px;
                                        text-align: left;
                                    }

                                    #fraudModal th {
                                        background-color: #6a0dad;
                                        color: white;
                                        font-weight: bold;
                                    }
                                </style>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const modal = document.getElementById('fraudModal');
                                        const modalContent = document.getElementById('fraudModalContent');
                                        const modalClose = document.getElementById('fraudModalClose');

                                        modalClose.addEventListener('click', () => {
                                            modal.style.display = 'none';
                                            modalContent.innerHTML = '';
                                        });

                                        window.addEventListener('click', e => {
                                            if (e.target === modal) {
                                                modal.style.display = 'none';
                                                modalContent.innerHTML = '';
                                            }
                                        });

                                        document.querySelectorAll('.fraud-checker').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const phone = this.dataset.phone;
                                                console.log(phone);
                                                if (!phone) return alert("Phone number not found!");

                                                fetch('https://bdcourier.com/api/courier-check?phone=' + encodeURIComponent(phone), {
                                                        method: 'POST',
                                                        headers: {
                                                            'Authorization': 'H5F4iFGTTVRGh3knHBqgggqi315Pxcf1a19S6Th4bW1RNeQsV7XQjvH0kBKd',
                                                            'Content-Type': 'application/json',
                                                        },
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        modalContent.innerHTML = ''; // Clear previous

                                                        if (!data || !data.courierData) {
                                                            modalContent.innerHTML = '<p style="padding:20px;">No data found.</p>';
                                                            modal.style.display = 'flex';
                                                            return;
                                                        }

                                                        const courierData = data.courierData;
                                                        const table = document.createElement('table');
                                                        table.classList.add('courier-table');

                                                        // Header row
                                                        table.innerHTML = `
                                                            <tr>
                                                                <th>Logo</th>
                                                                <th>Courier Name</th>
                                                                <th>Total Parcel</th>
                                                                <th>Successful</th>
                                                                <th>Cancelled</th>
                                                                <th>Success Rate</th>
                                                            </tr>
                                                        `;

                                                        // Each courier (excluding summary)
                                                        for (const key in courierData) {
                                                            if (key === 'summary') continue;

                                                            const courier = courierData[key];
                                                            const tr = document.createElement('tr');

                                                            tr.innerHTML = `
                                                                            <td><img src="${courier.logo}" alt="${courier.name}" width="40" /></td>
                                                                            <td>${courier.name}</td>
                                                                            <td>${courier.total_parcel}</td>
                                                                            <td>${courier.success_parcel}</td>
                                                                            <td>${courier.cancelled_parcel}</td>
                                                                            <td>${courier.success_ratio}%</td>
                                                                        `;

                                                            table.appendChild(tr);
                                                        }

                                                        // Summary Row
                                                        const summary = courierData.summary || {};
                                                        const summaryRow = document.createElement('tr');
                                                        summaryRow.style.backgroundColor = '#f3f3f3';
                                                        summaryRow.innerHTML = `
                                                                    <td colspan="2"><strong>Total Summary</strong></td>
                                                                    <td><strong>${summary.total_parcel || 0}</strong></td>
                                                                    <td><strong>${summary.success_parcel || 0}</strong></td>
                                                                    <td><strong>${summary.cancelled_parcel || 0}</strong></td>
                                                                    <td><strong>${summary.success_ratio || 0}%</strong></td>
                                                                `;
                                                        table.appendChild(summaryRow);

                                                        modalContent.appendChild(table);
                                                        modal.style.display = 'flex';
                                                    })
                                                    .catch(err => {
                                                        alert("Failed to fetch data.");
                                                        console.error(err);
                                                    });
                                            });
                                        });
                                    });
                                </script>


                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                <div class="custom-paginate">
                    {{$show_data->links('pagination::bootstrap-4')}}
                </div>
            </div> <!-- end card body-->

        </div> <!-- end card -->
    </div><!-- end col-->
</div>
</div>
<!-- Assign User End -->
<div class="modal fade" id="asignUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.order.assign')}}" id="order_assign">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Select..</option>
                            @foreach($users as $key=>$value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Assign User End-->

<!-- Assign User End -->
<div class="modal fade" id="changeStatus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.order.status')}}" id="order_status_form">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="">Select..</option>
                            @foreach($orderstatus as $key=>$value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Assign User End-->

<!-- pathao coureir start -->
@foreach($show_data as $key=>$value)
<div class="modal fade" id="pathao{{$value->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pathao Courier - {{$value->invoice_id}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.order.pathao')}}" id="order_sendto_pathao">

                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{$value->id}}">
                        <label for="pathaostore" class="form-label">Store</label>
                        <select name="pathaostore" id="pathaostore" class="pathaostore form-control">
                            <option value="">Select Store...</option>
                           
                            @if(isset($pathaostore['data']['data']))
                            @foreach($pathaostore['data']['data'] as $key=>$store)
                            <option value="{{$store['store_id']}}">{{$store['store_name']}}</option>
                            @endforeach
                            @else
                            @endif
                        </select>
                        @if ($errors->has('pathaostore'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('pathaostore') }}</strong>
                        </span>
                        @endif
                    </div>
                    <!-- form group end -->
                    <div class="form-group mt-3">
                        <label for="pathaocity" class="form-label">City</label>
                        <select name="pathaocity" id="pathaocity" class="chosen-select pathaocity form-control" style="width:100%">
                            <option value="">Select City...</option>
                            @if(isset($pathaocities['data']['data']))
                            @foreach($pathaocities['data']['data'] as $key=>$city)
                            <option value="{{$city['city_id']}}">{{$city['city_name']}}</option>
                            @endforeach
                            @else
                            @endif
                        </select>
                        @if ($errors->has('pathaocity'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('pathaocity') }}</strong>
                        </span>
                        @endif
                    </div>
                    <!-- form group end -->
                    <div class="form-group mt-3">
                        <label for="" class="form-label">Zone</label>
                        <select name="pathaozone" id="pathaozone" class="pathaozone chosen-select form-control  {{ $errors->has('pathaozone') ? ' is-invalid' : '' }}" value="{{ old('pathaozone') }}" style="width:100%">
                        </select>
                        @if ($errors->has('pathaozone'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('pathaozone') }}</strong>
                        </span>
                        @endif
                    </div>
                    <!-- form group end -->
                    <div class="form-group mt-3">
                        <label for="" class="form-label">Area</label>
                        <select name="pathaoarea" id="pathaoarea" class="pathaoarea chosen-select form-control  {{ $errors->has('pathaoarea') ? ' is-invalid' : '' }}" value="{{ old('pathaoarea') }}" style="width:100%">
                        </select>
                        @if ($errors->has('pathaoarea'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('pathaoarea') }}</strong>
                        </span>
                        @endif
                    </div>
                    <!-- form group end -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!-- pathao courier  End-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".checkall").on('change', function() {
            $(".checkbox").prop('checked', $(this).is(":checked"));
        });

        // order assign
        $(document).on('submit', 'form#order_assign', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var method = $(this).attr('method');
            let user_id = $(document).find('select#user_id').val();

            var order = $('input.checkbox:checked').map(function() {
                return $(this).val();
            });
            var order_ids = order.get();

            if (order_ids.length == 0) {
                toastr.error('Please Select An Order First !');
                return;
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    user_id,
                    order_ids
                },
                success: function(res) {
                    if (res.status == 'success') {
                        toastr.success(res.message);
                        window.location.reload();

                    } else {
                        toastr.error('Failed something wrong');
                    }
                }
            });

        });

        // order status change
        $(document).on('submit', 'form#order_status_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var method = $(this).attr('method');
            let order_status = $(document).find('select#order_status').val();

            var order = $('input.checkbox:checked').map(function() {
                return $(this).val();
            });
            var order_ids = order.get();

            if (order_ids.length == 0) {
                toastr.error('Please Select An Order First !');
                return;
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    order_status,
                    order_ids
                },
                success: function(res) {
                    if (res.status == 'success') {
                        toastr.success(res.message);
                        window.location.reload();

                    } else {
                        toastr.error('Failed something wrong');
                    }
                }
            });

        });
        // order delete
        $(document).on('click', '.order_delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var order = $('input.checkbox:checked').map(function() {
                return $(this).val();
            });
            var order_ids = order.get();

            if (order_ids.length == 0) {
                toastr.error('Please Select An Order First !');
                return;
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    order_ids
                },
                success: function(res) {
                    if (res.status == 'success') {
                        toastr.success(res.message);
                        window.location.reload();

                    } else {
                        toastr.error('Failed something wrong');
                    }
                }
            });

        });

        // multiple print
        $(document).on('click', '.multi_order_print', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var order = $('input.checkbox:checked').map(function() {
                return $(this).val();
            });
            var order_ids = order.get();

            if (order_ids.length == 0) {
                toastr.error('Please Select Atleast One Order!');
                return;
            }
            $.ajax({
                type: 'GET',
                url,
                data: {
                    order_ids
                },
                success: function(res) {
                    if (res.status == 'success') {
                        console.log(res.items, res.info);
                        var myWindow = window.open("", "_blank");
                        myWindow.document.write(res.view);
                    } else {
                        toastr.error('Failed something wrong');
                    }
                }
            });
        });
        // multiple courier
        $(document).on('click', '.multi_order_courier', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var order = $('input.checkbox:checked').map(function() {
                return $(this).val();
            });
            var order_ids = order.get();

            if (order_ids.length == 0) {
                toastr.error('Please Select An Order First !');
                return;
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    order_ids
                },
                success: function(res) {
                    if (res.status == 'success') {
                        toastr.success(res.message);
                        window.location.reload();

                    } else {
                        toastr.error('Failed something wrong');
                    }
                }
            });

        });
    })
</script>
@endsection