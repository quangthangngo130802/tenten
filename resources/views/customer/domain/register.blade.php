@extends('backend.layouts.master')

@section('content')
<div class="content">
    <div class="container">
        <h2 class="text-center pb-4">
            Chọn các tên miền mong muốn <span>(Có thể chọn nhiều tên miền cùng lúc)</span>
        </h2>

        <div class="d-flex">
            <div class="table-wrapper" style="flex: 80%; margin-right: 20px;">
                <div class="slider">
                    <table>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>TÊN MIỀN</th>
                                <th>Giá (VND/năm)</th>
                                <th>{{ $namedomain }}</th>
                            </tr>
                        </thead>
                        <tbody id="domain-list"></tbody>
                    </table>
                </div>
                <div class="pagination-container">
                    <button id="prev-btn">Prev</button>
                    <button id="next-btn">Next</button>
                </div>
            </div>

            <div class="cart-section">
                <div class="cart">
                    <h3>Giỏ hàng</h3>
                    <p><strong>Tổng số tên miền:</strong> <span id="total">0</span></p>

                    <ul id="cart-items">
                        {{-- @forelse ($cart as $item) --}}
                        @if($cart)
                        @forelse ($cart->details as $detail)
                        @if ($detail->type == 'domain')
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 5px 0;">
                            <span>

                                {{ $detail->domain.$detail->domain_extension }}
                            </span>
                            <span>{{ number_format($detail->price) }} VND</span>
                            <button class="btn_delete" data-id='{{ $detail->id }}'
                                data-domain='{{ $detail->domain_extension }}' data-name='{{ $detail->domain}}'
                                style="color: red; border: none; background: none; cursor: pointer;">✖</button>
                        </li>
                        @endif
                        @endforeach
                        @endif

                        {{-- @empty --}}
                        {{-- <p>Chưa có tên miền trong giỏ hàng</p> --}}
                        {{-- @endforelse --}}

                    </ul>
                    @if($cart)
                        @php
                        $types = ['hosting' => \App\Models\Hosting::class, 'email' => \App\Models\Email::class, 'cloud' =>
                        \App\Models\Cloud::class];
                        @endphp

                        @foreach ($types as $type => $model)
                            @if ($cart->details->contains('type', $type))
                            <p><strong>{{ ucfirst($type) }}</strong></p>
                            <ul>
                                @foreach ($cart->details as $detail)
                                    @if ($detail->type == $type)
                                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 5px 0;">
                                        <span>
                                            @php
                                            $product = $model::find($detail->product_id);
                                            @endphp
                                            {{ $product->package_name }}
                                        </span>
                                        <span>{{ number_format($detail->price , 0, ',', '.') }} VND</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                            @endif
                        @endforeach
                    @endif



                    <p style="display: flex; justify-content: space-around">
                        <strong style="padding-left: 28px">Tổng tiền:</strong>
                        <strong id="total-price" class="text-danger">
                            {{ number_format($cart?->total_price ?? 0, 0, ',', '.') }} VND
                        </strong>
                    </p>

                    <!-- Thêm dòng này -->
                </div>
                <div class="promo">
                    <p><strong>Khuyến mãi:</strong> Tên miền quốc gia <b>.VN</b> đang giảm <b>100%</b> giá dịch vụ +
                        tặng miễn phí 1 tên miền <b>.shop</b></p>
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;">
                    <a href="{{ route('customer.cart.listcart') }}" class="p-3" style="border: 1px solid; background: #007bff; color: white">Tiếp tục</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const domains = @json($domains) || [];
        const domainKeys = Object.entries(domains) || [];
        let selectedDomainsWithPrice = {};
        const name = '{{ $namedomain }}';

        let cartDetails = @json($cartDetails);
        let currentPage = 0;
        const itemsPerPage = 15;


        function renderDomainList() {
            const $domainList = $('#domain-list');
            $domainList.empty();

            const start = currentPage * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedDomains = domainKeys.slice(start, end);

            let cartDetails = @json($cartDetails);

            paginatedDomains.forEach((domain, index) => {
                const item = domain[1]['total'];
                const domainName = domain[0];

                // Kiểm tra xem domainName có trong cartDetails hay không
                const isChecked = cartDetails.some(cartItem => cartItem.domain_extension === domainName && cartItem.domain == name );

                const row = `
                    <tr>
                        <td>${start + index + 1}</td>
                        <td>${domainName}</td>
                        <td>${item.toLocaleString()} VND</td>
                        <td>
                            <input type="checkbox" value="${domainName}" data-price="${item}" class="domain-checkbox" ${isChecked ? 'checked' : ''}>
                        </td>
                    </tr>
                `;
                $domainList.append(row);
            });

        }

        function toggleDomain(checkbox) {
            const domain = checkbox.value;
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;

            if (checkbox.checked) {
                selectedDomainsWithPrice[domain] = price;
                const name_domain = checkbox.value;
                addToCart(name_domain, price);

            } else {
                delete selectedDomainsWithPrice[domain];
                deleteToCart(domain, price);
            }

            // updateCart(domain);
        }

        function addToCart(name_domain, price) {
            $.ajax({
                url: '/customer/domain/cart',
                method: 'POST',
                data: {
                    domain: name_domain,
                    nameDomain: name,
                    price: price,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Cập nhật giỏ hàng sau khi thành công
                    if (response.success) {
                        console.log('Giỏ hàng đã được cập nhật.');
                        updateCart(response.listdomain, response.total, response.count);
                        // renderCart(response.cart); // Hàm này sẽ render giỏ hàng lên giao diện
                    } else {
                        console.log('Có lỗi xảy ra khi cập nhật giỏ hàng.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                }
            });
        }

        function deleteToCart(name_domain, price) {
            $.ajax({
                url: '/customer/domain/delete-cart',
                method: 'POST',
                data: {
                    domain: name_domain,
                    nameDomain: name,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {

                        console.log('Xóa thanh công .');
                        updateCart(response.listdomain, response.total, response.count);

                    } else {
                        console.log('Lỗi.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                }
            });
        }

        $(document).on('click', '.btn_delete', function () {
            const itemId = $(this).attr('data-id');
            const domain = $(this).attr('data-domain');
            const domain_name = $(this).attr('data-name');
            $.ajax({
                url: `/customer/domain/remove/${itemId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Xóa thành công !');
                        const checkbox = document.querySelector(`.domain-checkbox[value="${domain}"]`);
                        if (checkbox) {
                            if(name == domain_name){
                                checkbox.checked = false;
                            }

                            updateCart(response.listdomain, response.total, response.count);
                        }

                    } else {
                            console.log('Lỗi!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                }
            });
        });

        function updateCart(listdomain, total, count){
            let totalPrice = 0;
            const cartList = document.getElementById('cart-items');
            const totalPriceElement = document.getElementById('total-price');
            cartList.innerHTML = '';
                listdomain.forEach( item => {
                    const price = Number(item['price']) || 0;
                     const li = `
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 5px 0;">
                                <span>${item['domain']}${item['domain_extension']}</span>
                                <span>${price.toLocaleString()} VND</span>
                                <button class="btn_delete" data-id='${item['id']}' data-domain='${item['domain_extension']}'  data-name='${item['domain']}' style="color: red; border: none; background: none; cursor: pointer;">✖</button>
                            </li>
                        `;
                    cartList.innerHTML += li;
                });

                totalPriceElement.textContent = total.toLocaleString() + ' VND';
                $(".notification").text(count);

        }

        $('#domain-list').on('change', '.domain-checkbox', function() {
            toggleDomain(this);
        });

        $('#prev-btn').click(function() {
            const maxPages = Math.ceil(domainKeys.length / itemsPerPage) - 1;
            currentPage = (currentPage === 0) ? maxPages : currentPage - 1;
             renderDomainList();
        });

        $('#next-btn').click(function() {
            const maxPages = Math.ceil(domainKeys.length / itemsPerPage) - 1;
            currentPage = (currentPage >= maxPages) ? 0 : currentPage + 1;
            renderDomainList();
        });


        renderDomainList();
    });
</script>
@endpush

@push('styles')
<style>
    .container {
        max-width: 80%;
        margin: auto;
        background: white;
        padding: 20px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    th {
        background: #333;
        color: white;
    }

    input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }

    .cart {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .cart h3 {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border-radius: 5px 5px 0 0;
        text-align: center;
    }

    #total {
        color: red;
    }

    .promo {
        border: 1px solid red;
        padding: 10px;
        border-radius: 5px;
        background-color: #fff;
        margin-top: 10px;
    }

    .promo b {
        color: blue;
    }

    .pagination-container {
        margin-top: 10px;
        text-align: center;
    }

    button {
        padding: 8px 15px;
        margin: 5px;
        border: none;
        background: #007bff;
        color: white;
        cursor: pointer;
        border-radius: 3px;
    }

    button:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
</style>
@endpush
