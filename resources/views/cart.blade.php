<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart | Baby World</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>

  <!--============ HEADER (ringkas, silakan sesuaikan) ==============-->
  <header class="header">
    <div class="header__top">
      <div class="header__container container">
        <div class="header__contact">
          <span>0865-9712-0151</span>
          <span><a href="#">Our Location</a></span>
        </div>

        <p class="header__alert-news">
          <a href="#">Super Value Deals - Save more with coupons</a>
        </p>

        <div class="header__contact">
          <span><a href="#">Help Center</a></span>
          <span><a href="#">Log In / Sign Up</a></span>
        </div>
      </div>
    </div>

    <nav class="nav container">
      <a href="#" class="nav__logo">
        <img src="{{ asset('assets/img/feelin.png') }}" alt="" class="nav__logo-img">
      </a>

      <div class="nav__menu" id="nav-menu">
        <ul class="nav__list">
          <li class="nav__item"><a href="#" class="nav__link">Home</a></li>
          <li class="nav__item"><a href="{{ route('shop') }}" class="nav__link">Shop</a></li>
          <li class="nav__item"><a href="#" class="nav__link">My Account</a></li>
          <li class="nav__item"><a href="#" class="nav__link">Compare</a></li>
          <li class="nav__item"><a href="#" class="nav__link">Contact</a></li>
          <li class="nav__item"><a href="#" class="nav__link">Login</a></li>
        </ul>

        <form action="{{ route('shop') }}" method="GET" class="header__search">
          <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search for items..." class="form__input">
          <button type="submit" class="search__btn">
            <i class='bx bx-search-alt'></i>
          </button>
        </form>
      </div>

      <div class="header__user-actions">
        <a href="#" class="header__action-btn">
          <i class='bx bxs-heart'></i><span class="count">8</span>
        </a>
        <a href="{{ route('cart.index') }}" class="header__action-btn">
          <i class='bx bxs-cart-alt'></i><span class="count">{{ count(session('cart', [])) }}</span>
        </a>
        <a href="#" class="header__action-btn">
          <i class='bx bxs-envelope'></i>
        </a>
      </div>
    </nav>
  </header>

  <!--============ BREADCRUMB ==============-->
  <section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
      <li><a href="#" class="breadcrumb__link">Home</a></li>
      <li><span class="breadcrumb__link">></span></li>
      <li><span class="breadcrumb__link">Cart</span></li>
    </ul>
  </section>

      @if (session('success'))
  <div class="alert alert-success" 
       style="background-color:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
      {{ session('success') }}
  </div>
  @endif

  <!--============ CART TABLE ==============-->
  <section id="cart" class="section-p1">
    @if(session('success'))
      <div style="color: #2e7d32; font-weight: 600; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div style="color: #c62828; font-weight: 600; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <table width="100%">
      <thead>
        <tr>
          <td>Remove</td>
          <td>Item</td>
          <td>Product</td>
          <td>Price</td>
          <td>Quantity</td>
          <td>Subtotal</td>
        </tr>
      </thead>
      <tbody id="cart-body">
        @forelse($items as $item)
          <tr>
            <td>
              <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                @csrf
                <input type="hidden" name="id_produk" value="{{ $item['id_produk'] }}">
                <button type="submit" class="remove-btn">×</button>
              </form>
            </td>
            <td>
              <img src="{{ asset($item['foto']) }}" alt="" width="60">
            </td>
            <td>{{ $item['nama_produk'] }}</td>
            <td>Rp{{ number_format($item['harga'], 0, ',', '.') }}</td>
            <td style="display:flex; gap:6px; align-items:center;">
              <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id_produk" value="{{ $item['id_produk'] }}">
                <input type="hidden" name="action" value="dec">
                <button type="submit" class="normal">−</button>
              </form>

              <input type="number" class="qty" value="{{ $item['quantity'] }}" min="1" readonly style="width:60px;text-align:center;">

              <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id_produk" value="{{ $item['id_produk'] }}">
                <input type="hidden" name="action" value="inc">
                <button type="submit" class="normal">+</button>
              </form>
            </td>
            <td>Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr><td colspan="6">Keranjang Anda kosong.</td></tr>
        @endforelse
      </tbody>
    </table>
  </section>

  <!--============ COUPON & TOTALS ==============-->
  <section id="cart-add" class="section-p1">
    <div id="coupon">
      <h3>Apply Coupon</h3>
      <div>
          <input type="text" name="kode_voucher" placeholder="Enter Your Coupon" class="form__input" value="{{ $voucher_code }}">
          <button type="submit" class="normal">Apply</button>
        </form>

        @if(!empty($voucher_msg))
          <p 
            @if(!empty($voucher_valid) && $voucher_valid) 
                style="color:green; font-weight:bold; margin-top:10px;" 
            @else 
                style="color:red; font-weight:bold; margin-top:10px;" 
            @endif
          >
            {{ $voucher_msg }}
          </p>
      @endif
      </div>
    </div>

    <div id="subtotal">
      <h3>Cart Totals</h3>
      <table>
        <tr>
          <td>Cart Total</td>
          <td id="cart-total">
            Rp{{ number_format($total, 0, ',', '.') }}
            @if(($discount ?? 0) > 0)
              <br><span style="font-size:13px; color:green;">-{{ $discount }}% ({{ $voucher_code }})</span>
            @endif
          </td>
        </tr>
        <tr>
          <td>Shipping</td>
          <td>Free</td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td><strong id="total-all">Rp{{ number_format($final_total, 0, ',', '.') }}</strong></td>
        </tr>
      </table>

      <div class="button__co">
        <a href="#" class="normal">Checkout</a>
      </div>
    </div>
  </section>

  <!--============ FOOTER (ringkas) ==============-->
  <footer class="footer__container">
    <div class="footer__bottom">
      <p class="copyright">&copy; 2024 BabyWorld. All rights reserved</p>
      <span class="designer">Designed by DianNadineAkhtar</span>
    </div>
  </footer>

  <script>
    localStorage.removeItem('cartItems');
  </script>
</body>
</html>
