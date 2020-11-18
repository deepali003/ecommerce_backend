@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="{{ url('/admin/dashboard')}}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
    <a href="#">Manage App</a> 
    <a href="#" class="current">View App</a> </div>
    <h1>Share App</h1>
    @if(Session::has('flash_message_error'))
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{!! session('flash_message_error') !!}</strong>
            </div>
        @endif   
        @if(Session::has('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{!! session('flash_message_success') !!}</strong>
            </div>
        @endif
  </div>
  <!-- <div style="margin-left:20px;">
    <a href="{{ url('/admin/export-products') }}" class="btn btn-primary btn-mini">Export</a>
  </div> -->
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <!-- <h5>Products</h5> -->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Subject</th>
                  <th>Body</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($fetchApps as $fetchApp)
                <tr class="gradeX">
                  <td class="center">{!! $fetchApp->subject !!}</td>
                  <td class="center">{!! $fetchApp->body !!}</td>
                  <td class="center">
                    <a href="{{ url('/admin/edit-app/'.$fetchApp->id) }}" class="btn btn-primary btn-mini">Edit</a> 
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection