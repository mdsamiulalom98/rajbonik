@extends('frontEnd.layouts.hostel.master')
@section('title', 'Hostel Dashboard')
@section('content')
<style>
    @media only screen and (min-width: 320px) and (max-width: 767px) {
    .des-item {
        margin: 5px 0;
    }
}
</style>
<div class="page-header">
    <h5>Dashboard</h5>
</div>
	<div class="page-content sm-order-2">
	    <div class="row">
	        <div class="col-sm-3 main-Pie">
	            <div id="chartContainer" style="height: 200px; width: 100%;"></div>
	            <a href="{{route('hostel.dashboard')}}">
		            <div class="inner-chart">
		                <h6>total value</h6>
		                <h3> ৳ {{ number_format($total_amount)}}</h3>
		                <p>{{$total_order}} Orders</p>
		            </div>
	            </a>
	        </div>
	        <!--end-col-->
	        <div class="col-sm-9">
	            <div class="chart-des">
	                <!--new-row-start-->
	                <div class="row">
	                    <div class="col-sm-4">  
	                      <a href="">
	                        <div class="des-item" style="border-left:6px solid #56A17B; padding-left:20px;">
	                            <h6>Delivered</h6>
	                            <h4>@if($total_complete > 0) {{number_format(($total_complete*100)/$total_order,2)}} @else 0 @endif%</h4>
	                            <h6>{{$total_complete}} orders | ৳ {{$delivery_amount}}</h6>
	                        </div>
	                        </a>
	                    </div>
	                    <!--end-col-->
	                    <div class="col-sm-4">
	                      <a href="">
	                        <div class="des-item" style="border-left:6px solid #ffcd00; padding-left:20px;">
	                            <h6>Delivery Processing</h6>
	                            <h4>@if($total_process > 0) {{number_format(($total_process*100)/$total_order,2)}} @else 0 @endif%</h4>
	                            <h6>{{$total_process}} orders | ৳ {{$process_amount}}</h6>
	                        </div>
	                       </a>
	                    </div>
	                    <!--end-col-->
	                    <div class="col-sm-4">
	                      <a href="">
	                        <div class="des-item" style="border-left:6px solid #ff4c49;padding-left:20px;">
	                            <h6>Returned</h6>
	                            <h4>@if($total_return > 0) {{number_format(($total_return*100)/$total_order,2)}} @else 0 @endif%</h4>
	                            <h6>{{$total_return}} orders | ৳ {{$return_amount}}</h6>
	                        </div>
	                      </a>
	                    </div>
	                    <!--end-col-->
	                </div>
	                <!--new-row-end-->
	            </div>
	        </div>
	        <!--end-col-->
	    </div>
	    <!--end-row-->
	</div>
	
@endsection
@push('script')
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
<script>
    window.onload = function () {
    var options = {
    	animationEnabled: true,
    	title: {
    		text: ""
    	},
    	data: [{
    		type: "doughnut",
    		innerRadius: "80%",
    		dataPoints: [
    			{ label: "", y: {{$delivery_amount?$delivery_amount:1}} ,color: "#56A17B"},
    			{ label: "", y: {{$process_amount}} , color : "#ffcd00"},
    			{ label: "", y: {{$return_amount}} , color : "#ff4c49"},
    			
    		]
    	}]
    };
    $("#chartContainer").CanvasJSChart(options);
    
    }
</script>
@endpush
