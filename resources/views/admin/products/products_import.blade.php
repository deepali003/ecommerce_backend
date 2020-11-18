@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
    <a href="{{ url('/admin/dashboard')}}" title="Go to Home" class="tip-bottom">
    <i class="icon-home"></i> Home</a> 
    <a href="#">Product</a> 
    <a href="#" class="current">Import Product</a> </div>
    <h1>Product</h1>
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
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Import Products</h5>
          </div>
          <div class="widget-content nopadding">
            <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ route('import') }}">
            {{ csrf_field() }}
            
              <div class="control-group">
                <label class="control-label">Excel file</label>
                <div class="controls">
                <input type="file" name="file" class="form-control">
                <br>
                
                </div>
              </div>
             
              <!-- <div class="control-group">
                <label class="control-label">Enable</label>
                <div class="controls">
                  <input type="checkbox" name="status" id="status" value="1">
                </div>
              </div> -->
              <div class="form-actions">
              <button class="btn btn-success">Import Bulk Data</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection