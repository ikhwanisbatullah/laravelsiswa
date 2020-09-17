@extends('layouts.master')
@section('content')
<!-- MAIN -->
<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<!-- BASIC TABLE -->
							<div class="panel">
								<div class="panel-heading">
                                    <h3 class="panel-title">Add New Posts</h3>
                                    
								</div>
								<div class="panel-body">
									<div class="row">
                                        <div class="col-md-8">
                                        <form action="{{route('posts.create')}}" method="POST" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="form-group{{$errors->has('title') ? 'has-error' : ''}}" >
                                                <label for="exampleInputEmail1">Title</label>
                                                <input name="title" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Title" value="{{old('title')}}">
                                                @if($errors->has('title'))
                                                    <span class="help-block">{{$errors->first('title')}}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Content</label>
                                                <textarea name="content" class="form-control" id="content" rows="3">{{old('content')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> Choose
                                                </a>
                                            </span>
                                            <input id="thumbnail" class="form-control" type="text" name="filepath">
                                            </div>
                                            <img id="holder" style="margin-top:15px;max-height:100px;">
                                            <div class="input group">
                                            <input type="submit" class="btn btn-info" value="Submit">
                                            </div>
                                            
                                        </div>
                                    </div>
								</div>
							</div>
                            <!-- END BASIC TABLE -->
                        </div>
                    </div>
                </div>
            </div>
</div>

@stop

@section('footer')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#content' ) )
        .catch( error => {
            console.error( error );
        } );
        $(documennt).ready(function(){
            $('#lfm').filemanager('image');
        });   
</script>
@stop