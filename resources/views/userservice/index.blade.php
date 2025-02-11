@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')
<div class="title">DỊCH VỤ ĐANG SỬ DỤNG</div>

{{-- <div class="status-buttons">
    <button class="status-button green">Đang sử dụng</button>
    <button class="status-button yellow">Sắp hết hạn</button>
    <button class="status-button red">Hết hạn</button>
</div> --}}

<table style="border: 2px solid #c2bfbf">
    <thead>
        <tr>
            <th style="width: 60%;"><div style="margin-left: 70px">Dịch vụ</div></th>
            <th style="width: 13%; text-align: center"><button class="status-button green">Đang sử dụng</button></th>
            <th style="width: 13%;text-align: center"><button class="status-button yellow">Sắp hết hạn</button></th>
            <th style="width: 13%;text-align: center"><button class="status-button red">Hết hạn</button></th>
        </tr>
    </thead>
    <tbody>
        @foreach($types as $type => $data)
            {{-- @if (Auth::user()->role_id == 1)
                @if($data['active_count'] > 0 || $data['expiring_soon_count'] > 0 || $data['expired_count'] > 0)
                    <tr>
                        <td><div style="margin-left: 70px; font-weight: bold">{{ ucfirst($type) }}</div></td>
                        <td style="text-align: center"><a href="{{ route('service.' . $type . '.list.'.$type) }}">{{ $data['active_count'] }}</a> </td>
                        <td style="text-align: center"><a href="{{ route('service.' . $type . '.list.'.$type, ['date' => 'expire_soon']) }}">{{ $data['expiring_soon_count'] }}</a></td>
                        <td style="text-align: center"><a href="{{ route('service.' . $type . '.list.'.$type, ['date' => 'expire']) }}">{{ $data['expired_count'] }}</a></td>
                    </tr>
                @endif
            @else --}}
                @if($data['active_count'] > 0 || $data['expiring_soon_count'] > 0 || $data['expired_count'] > 0)
                    <tr>
                        <td><div style="margin-left: 70px; font-weight: bold">{{ ucfirst($type) }}</div></td>
                        <td style="text-align: center"><a href="{{ route('customer.service.list.service', ['type' => $type]) }}">{{ $data['active_count'] }}</a> </td>
                        <td style="text-align: center"><a href="{{ route('customer.service.list.service', ['type' => $type, 'date' => 'expire_soon']) }}">{{ $data['expiring_soon_count'] }}</a></td>
                        <td style="text-align: center"><a href="{{ route('customer.service.list.service', ['type' => $type, 'date' => 'expire']) }}">{{ $data['expired_count'] }}</a></td>
                    </tr>
                @endif
            {{-- @endif --}}
        @endforeach
    </tbody>

</table>
@endsection

@push('styles')

<style>
    .title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        /* text-align: center */
    }

    th,
    td {
        /* border: 1px solid #ddd; */
        padding: 10px;
        /* text-align: center; */
    }

    th {
        background-color: #f9f9f9;
        font-weight: bold;
    }

    thead {
        border-bottom: 1px solid #ddd;
    }

    thead tr th,
    tbody tr td {
        padding: 18px 0px !important;
    }

    thead tr th:first-child,
    tbody tr td:first-child {
        margin: 0px 20px !important;
    }

    thead tr th:last-child,
    tbody tr td:last-child {
        margin: 0px 20px !important;
    }

    .status-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .status-button {
        padding: 6px 15px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        color: #fff;
        cursor: pointer;
    }

    .status-button.green {
        background-color: #28a745;
    }

    .status-button.yellow {
        background-color: #ffc107;
    }

    .status-button.red {
        background-color: #dc3545;
    }
</style>




@endpush
