<div class="navbar navbar-expand-xl navbar-light navbar-static px-0">
  <div class="d-flex flex-1 pl-3">
    <div class="navbar-brand wmin-0 mr-1">
      <a href="index.html" class="d-inline-block">
        <img src="{{ asset('assets/images/lta-logo-text.png') }}" class="d-none d-sm-block" alt="">
        <img src="{{ asset('assets/images/lta-logo-text.png') }}" class="d-sm-none" alt="">
      </a>
    </div>
  </div>

  <div class="d-flex w-100 w-xl-auto overflow-auto overflow-xl-visible scrollbar-hidden border-top border-top-xl-0 order-1 order-xl-0">
    <ul class="navbar-nav navbar-nav-underline flex-row text-nowrap mx-auto">
      <li class="nav-item mega-menu-full nav-item-dropdown-xl">
        
      </li>
    </ul>
  </div>

  <div class="d-flex flex-xl-1 justify-content-xl-end order-0 order-xl-1 pr-3">
    <ul class="navbar-nav navbar-nav-underline flex-row">		
      <li class="nav-item nav-item-dropdown-xl dropdown dropdown-user h-100">
        <a href="#" class="navbar-nav-link navbar-nav-link-toggler d-flex align-items-center">
          <img src="{{ asset('assets/images/user.png') }}" class="rounded-circle mr-xl-2" height="38" alt="">
          <span class="d-none d-xl-block">{{ $row['SpvName'] }}</span>
        </a>
      </li>
    </ul>
  </div>
</div>
<!-- /main navbar -->