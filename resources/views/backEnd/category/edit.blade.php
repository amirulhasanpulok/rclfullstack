@extends('backEnd.layouts.master')
@section('title','Category Edit')
@section('css')
<link href="{{asset('backEnd/assets/libs/select2/css/select2.min.css')}} rel="stylesheet" type="text/css" />
<link href="{{asset('backEnd/assets/libs/summernote/summernote-lite.min.css')}} rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('categories.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Category Edit</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('categories.update') }}" method="POST" class="row" data-parsley-validate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $edit_data->id }}">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ $edit_data->name }}" id="name" required>
                                @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    name="image" id="image">
                                @if($edit_data->image)
                                <img src="{{ asset($edit_data->image) }}" alt="" class="edit-image mt-2" width="80">
                                @endif
                                @error('image')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="icon" class="form-label">Icon</label>
                                <input type="file" class="form-control @error('icon') is-invalid @enderror"
                                    name="icon" id="icon">
                                @if($edit_data->icon)
                                <img src="{{ asset($edit_data->icon) }}" alt="" class="edit-image mt-2" width="40">
                                @endif
                                @error('icon')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    name="meta_title" value="{{ $edit_data->meta_title }}" id="meta_title">
                                @error('meta_title')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                    name="meta_description" rows="6" id="meta_description">{!! $edit_data->meta_description !!}</textarea>
                                @error('meta_description')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col mb-3">
                            <div class="form-group">
                                <label for="status" class="d-block">Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status" value="1" {{ $edit_data->status == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                @error('status')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" name="featured" value="1" {{ old('featured', $edit_data->featured ?? false) ? 'checked' : '' }}>
                                Featured Category
                            </label>
                        </div>

                        <div class="col mb-3">
                            <div class="form-group">
                                <label for="front_view" class="d-block">Front View</label>
                                <label class="switch">
                                    <input type="checkbox" name="front_view" value="1" {{ $edit_data->front_view == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                @error('front_view')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                         <div class="col mb-3">
                            <div class="form-group">
                                <label for="banner_image" class="d-block">banner image </label>
                                <label class="switch">
                                    <input type="checkbox" name="banner_image" value="1" {{ $edit_data->banner_image == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                @error('banner_image')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="submit" class="btn btn-success" value="Submit">
                        </div>
                    </form>


                </div> 
            </div> 
        </div> 
    </div>
</div>
@endsection


@section('script')
<script src="{{asset('backEnd/assets/libs/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('backEnd/assets/js/pages/form-validation.init.js')}}"></script>
<script src="{{asset('backEnd/assets/libs/select2/js/select2.min.js')}}"></script>
<script src="{{asset('backEnd/assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('backEnd/assets/libs//summernote/summernote-lite.min.js')}}"></script>
<script>
    $(".summernote").summernote({
        placeholder: "Enter Your Text Here",
    });
</script>

@endsection