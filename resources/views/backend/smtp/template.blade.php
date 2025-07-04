@extends('backend.layouts.master')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link fw-bold" id="info-tab" href="{{ route('smtp.email') }}">SMTP</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link active fw-bold" id="seo-tab" href="">Email nhận thông báo</a>
        </li>

    </ul>



    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h5 class="card-title">Cấu hình email</h5>
                        </div>

                        <form id="postForm" enctype="multipart/form-data" method="POST" action="{{ route('smtp.email.admin') }}">
                            @csrf
                            <label for="email" class="form-label fw-bold">Email </label>
                            <div class=" mb-3">
                                <input type="text" class="form-control iconpicker-input" name="email" id="email"
                                    placeholder="Nhập để  email" value="{{ $email ? $email->email:'' }}" required>
                                <div class="error-message text-danger"></div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" id="save">Lưu</button>
                              
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
