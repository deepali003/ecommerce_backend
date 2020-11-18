@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="{{ url('/admin/dashboard')}}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Products</a> <a href="#" class="current">Edit App</a> </div>
    <h1>App</h1>
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
            <h5>Edit App</h5>
          </div>
          <div class="widget-content nopadding">
            <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ url('admin/updateApp/'.$fetchapp->id) }}" name="edit_product" id="edit_product" novalidate="novalidate">{{ csrf_field() }}
           
              <div class="control-group">
                <label class="control-label">Subject</label>
                <div class="controls">
                  <input type="text" name="subject" id="subject" value="{{ $fetchapp->subject }}">
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Body</label>
                <div class="controls">
                  <input type="text" name="body" id="body" value="{{ $fetchapp->body }}">
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Enable</label>
                <div class="controls">
                  <input type="checkbox" name="status" id="status" @if($fetchapp->status == "1") checked @endif value="1">
                </div>
              </div>
              <div class="form-actions">
                <input type="submit" value="Update App" class="btn btn-success">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection