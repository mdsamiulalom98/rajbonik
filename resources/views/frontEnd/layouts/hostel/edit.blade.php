<link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style>
    .table__datas {
        max-height: 250px !important;
        overflow-y: auto;
    }

    .btn__section {
        padding: 15px;
        float: inline-end;
    }

    .btn__section input[type="submit"] {
        background-color: #008000;
        height: 40px;
        color: #fff;
        padding: 0px 10px;
        border-color: green;
        outline: none;
        border-radius: 5px;
    }

    .date__comment {
        display: flex;
        gap: 5px;
        width: 100%;
    }

    .date__comment label {
        padding: 10px 0;
    }

    .btn__section .order__clear {
        background: red;
        padding: 10px 15px;
        color: white;
        font-weight: 600;
        border-radius: 3px;
        cursor: pointer;
    }

    .btn__section button {
        background: green;
        padding: 8px 15px;
        color: white;
        font-weight: 600;
        border-radius: 3px;
    }

    .hostel_order_create button {
        background: green;
        padding: 10px 15px;
        border-radius: 3px;
        color: white;
        font-weight: 600;
    }

    button.clear__btn {
        background: red !important;
    }

    p.no__data__found {
        text-align: center;
        font-size: 20px;
        margin-top: 20px;
        color: #d3d3d3;
    }

    div#cartForm label {
        padding-top: 6px;
        font-weight: 600;
    }

    @media only screen and (min-width: 320px) and (max-width: 767px) {
        .modal-view-hostel {
            padding: 10px 8px;
            height: 85vh !important;
            overflow-y: auto !important;
        }

        .hostel_order_create button {
            margin-bottom: 20px;
        }
    }
</style>
<div class="modal-view-hostel quick-product">
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice__modal">
                        <div class="invoice_modal_header">
                            <div class="modal__name">
                                <h2>Order Edit</h2>
                            </div>
                            <div class="close-modals">
                                <h2><i class="fa-solid fa-xmark close-modal-hostel"></i></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="hostel_order_create cartForm">
                        <div class="addOrder">
                            @include('frontEnd.layouts.hostel.createForm')
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 order__update_form_work">
                    <div class="cartTable">
                        @include('frontEnd.layouts.hostel.cart_list')
                    </div>
                    <form action="{{ route('hostel.orders.updates') }}">
                        <div class="date__comment">
                            <div class="col-sm-6">
                                <label for="deliveryTime">Delivery Time *</label>
                                <input id="deliveryTime" value="{{ $order->delivery_time }}" name="deliveryTime" class="form-control" readonly required />
                            </div>
                            <div class="col-sm-6">
                                <label for="comment">Comment</label>
                                <input type="text" name="comment" class="form-control" id="comment"
                                    placeholder="Enter comment">
                            </div>
                        </div>
                        @php
                            $deliveryTime = \Carbon\Carbon::parse($order->delivery_time);
                            $currentTime = \Carbon\Carbon::now();
                            $status = $deliveryTime->lt($currentTime->addHours(9)) ? false : true;
                        @endphp
                        <div class="btn__section">
                            <input type="submit" value="Order Again" name="reorder" />
                            <a class="order__clear">Clear All</a>
                            <button type="submit" {{ $status == false ? 'disabled' : '' }} onclick="order_hostel_place()">Update</button>
                        </div>
                    </form>
                    {{-- <p class="no__data__found">No data found !</p> --}}

                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('public/frontEnd/') }}/js/jquery-3.7.1.min.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
<script>
    $('.close-modal-hostel').on('click', function() {
        $("#custom-modal").hide();
        $("#page-overlay").hide();
    });
</script>
<script>
    function cart_content() {
        $.ajax({
            type: "GET",
            url: "{{ route('hostel.order.cart_content') }}",
            dataType: "html",
            success: function(hostel_cart) {
                $(".cartTable").html(hostel_cart);
            },
        });
    }

    $(".order_hostel").on("click", function(e) {
        e.preventDefault();
        var selectedOption = $("#product_id").find(":selected");
        var id = selectedOption.val();
        var color = selectedOption.data("color");
        var size = selectedOption.data("size");
        var type = selectedOption.data("type");
        var qty = $("#qty").val();
        var comment = $("#comment").val();

        if (!id || !qty) {
            alert("Please select a product and enter quantity.");
            return;
        }
        if (id) {
            $.ajax({
                cache: "false",
                type: "GET",
                data: {
                    id,
                    color,
                    size,
                    type,
                    qty,
                    comment
                },
                url: "{{ route('hostel.order.cart_add') }}",
                dataType: "json",
                success: function(hostel_cart) {
                    return cart_content();
                },
            });
        }
    });
    $(".order__clear").on("click", function(e) {
        var id = 1;
        if (id) {
            $.ajax({
                cache: "false",
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('hostel.order.clear') }}",
                dataType: "json",
                success: function(cartinfo) {
                    return cart_content();
                },
            });
        }
    });
</script>
<script>
    function cart_content_edit(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('hostel.order.cart_content_edit') }}",
            dataType: "html",
            data: {
                id: id
            },
            success: function(cartinfo) {
                $(".cartForm").html(cartinfo);
            },
        });
    }

    $(".edit_hostel_order").on("click", function(e) {
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                cache: "false",
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('hostel.order.cart_edit') }}",
                dataType: "json",
                success: function(cartinfo) {
                    return cart_content_edit(id);
                },
            });
        }
    });
</script>
<script>
    // $(document).ready(function() {
    //     function formatDate(date) {
    //         const day = String(date.getDate()).padStart(2, '0');
    //         const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    //         const year = date.getFullYear();

    //         let hours = date.getHours();
    //         const minutes = String(date.getMinutes()).padStart(2, '0');
    //         const seconds = String(date.getSeconds()).padStart(2, '0');

    //         const amPm = hours >= 12 ? 'PM' : 'AM';
    //         hours = hours % 12 || 12; // Convert 0 to 12-hour format

    //         return `${day}-${month}-${year} ${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${amPm}`;
    //     }

    //     // Get tomorrow's date
    //     const now = new Date();
    //     const tomorrow = new Date(now);
    //     tomorrow.setDate(now.getDate() + 1);
    //     tomorrow.setHours(9, 0, 0); // Set time to 09:00:00

    //     // Append option to select element using jQuery
    //     $('#deliveryTime').val(formatDate(tomorrow));
    // });
</script>
