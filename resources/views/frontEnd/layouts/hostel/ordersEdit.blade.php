<form action="{{ route('hostel.orders.updates') }}">
    <div class="table__datas">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cartTable" class="">
                @foreach (Cart::instance('shopping')->content() as $value)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->qty }}</td>
                        <td>{{ $value->subtotal }}</td>
                        <td><span class="btn btn-xs btn-info edit_hostel_order" data-id="{{ $value->id }}"><i
                                    class="fa-regular fa-pen-to-square"></i></span> <span
                                class="btn btn-xs btn-danger cart__remove" data-id="{{ $value->id }}"><i
                                    class="fa-solid fa-trash-can"></i></span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="date__comment">
        <div class="col-sm-6">
            <label for="deliveryTime">Delivery Time *</label>
            <select id="deliveryTime" name="deliveryTime" class="form-select form-control">
                <option value="">-Please select-</option>
            </select>
        </div>
        <div class="col-sm-6">
            <label for="comment">Comment</label>
            <input type="text" name="comment" class="form-control" id="comment" placeholder="Enter comment">
        </div>
    </div>
    <div class="btn__section">
        <a class="order__clear">Clear All</a>
        <button type="submit" onclick="order_hostel_place()">Update</button>
    </div>

    {{-- <p class="no__data__found">No data found !</p> --}}

</form>
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
                $("#cartTable").html(hostel_cart);
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
                    id: id,
                    color: color,
                    size: size,
                    type: type,
                    qty: qty,
                    comment: comment
                },
                url: "{{ route('hostel.order.cart_add') }}",
                dataType: "json",
                success: function(hostel_cart) {
                    return cart_content();
                },
            });
        }
    });

    $(".cart__remove").on("click", function(e) {
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                cache: "false",
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('hostel.order.cart_remove') }}",
                dataType: "json",
                success: function(remove) {
                    console.log(remove);
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
                $("#cartForm").html(cartinfo);
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
                    console.log(cartinfo);
                    return cart_content_edit(id);
                },
            });
        }
    });
</script>
<script>
    // Function to format the date as dd-mm-yyyy hh:mm:ss
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
    }

    // Get tomorrow's date
    const now = new Date();
    const tomorrow = new Date(now);
    tomorrow.setDate(now.getDate() + 1);
    tomorrow.setHours(9, 0, 0); // Set time to 09:00:00

    const select = document.getElementById('deliveryTime');
    const option = document.createElement('option');
    option.value = formatDate(tomorrow);
    option.textContent = formatDate(tomorrow);
    select.appendChild(option);
</script>
