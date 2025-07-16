@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($partner) ? route('partners.update', $partner->id) : route('partners.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($partner))
                    @method('PUT')
                @endif

                <h5 class="section-title">Thông tin khách hàng</h5>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Họ tên -->
                        <div class="form-group row">
                            <label for="full_name" class="form-label">Họ tên</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" placeholder="Nhập họ tên"
                                value="{{ old('full_name', $partner->full_name ?? '') }}">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SĐT cá nhân -->
                        <div class="form-group row">
                            <label for="phone" class="form-label">SĐT Cá nhân</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" placeholder="Nhập số điện thoại"
                                value="{{ old('phone', $partner->phone ?? '') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Nhập email" value="{{ old('email', $partner->email ?? '') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Chức vụ -->
                        <div class="form-group row">
                            <label for="position" class="form-label">Chức vụ</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                id="position" name="position" placeholder="Nhập chức vụ"
                                value="{{ old('position', $partner->position ?? '') }}">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nguồn -->
                        <div class="form-group row">
                            <label for="source" class="form-label">Nguồn</label>
                            <input type="text" class="form-control @error('source') is-invalid @enderror" id="source"
                                name="source" placeholder="Nhập nguồn" value="{{ old('source', $partner->source ?? '') }}">
                            @error('source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Tên công ty -->
                        <div class="form-group row">
                            <label for="company" class="form-label">Tên công ty</label>
                            <input type="text" class="form-control @error('company') is-invalid @enderror" id="company"
                                name="company" placeholder="Nhập tên công ty"
                                value="{{ old('company', $partner->company ?? '') }}">
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SĐT công ty -->
                        <div class="form-group row">
                            <label for="company_phone" class="form-label">SĐT công ty</label>
                            <input type="text" class="form-control @error('company_phone') is-invalid @enderror"
                                id="company_phone" name="company_phone" placeholder="Nhập số điện thoại"
                                value="{{ old('company_phone', $partner->company_phone ?? '') }}">
                            @error('company_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mã số thuế -->
                        <div class="form-group row">
                            <label for="tax_code" class="form-label">Mã số thuế</label>
                            <input type="text" class="form-control @error('tax_code') is-invalid @enderror"
                                id="tax_code" name="tax_code" placeholder="Nhập mã số thuế"
                                value="{{ old('tax_code', $partner->tax_code ?? '') }}">
                            @error('tax_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ngành nghề -->
                        <div class="form-group row">
                            <label for="industry" class="form-label">Ngành nghề</label>
                            <input type="text" class="form-control @error('industry') is-invalid @enderror"
                                id="industry" name="industry" placeholder="Nhập ngành nghề"
                                value="{{ old('industry', $partner->industry ?? '') }}">
                            @error('industry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="area_id" class="form-label">Khu vực</label>
                            <select class="form-control @error('area_id') is-invalid @enderror" id="area_id"
                                name="area_id">
                                <option value="">-- Chọn khu vực --</option>
                               @forelse($areas as $key => $value)
                                    <option value="{{ $value->id }}"
                                        {{ old('area_id', $partner->area_id ?? '') == $value->id ? 'selected' : '' }}> {{ $value->name }}
                                    </option>
                               @empty

                               @endforelse

                            </select>
                            @error('area_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Địa chỉ -->
                        <div class="form-group row">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" placeholder="Nhập địa chỉ"
                                value="{{ old('address', $partner->address ?? '') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                placeholder="Nhập ghi chú" rows="3">{{ old('note', $partner->note ?? '') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Buttons -->
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success">{{ isset($partner) ? 'Cập nhật' : 'Lưu' }}</button>
                </div>
            </form>




        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
