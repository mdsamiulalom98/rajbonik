@extends('frontEnd.layouts.hostel.master')
@section('title','Hostel Profile')
@section('content')
	<div class="page-header">
		<h5>Hostel Profile 111</h5>
	</div>
	<div class="page-content">
		<div class="row">
			<div class="col-sm-10">
				<div class="text-left mb-3 mt-3">
					<a href="{{route('hostel.settings')}}" class="btn btn-primary"><i class="fa-solid fa-edit"></i> Edit</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-7">
				<div class="marchant-profile">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Name</td>
								<td>{{$profile->name}}</td>
							</tr>
							<tr>
								<td>Phone Name</td>
								<td>{{$profile->phone}}</td>
							</tr>
							<tr>
								<td>Phone Email</td>
								<td>{{$profile->email}}</td>
							</tr>
							<tr>
								<td>Address</td>
								<td>{{$profile->address}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection