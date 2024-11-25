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
        <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
          <i class="icon-make-group mr-2"></i>
          Menu
        </a>
      
        <div class="dropdown-menu dropdown-content">
          <div class="d-xl-flex">
            <div class="d-flex flex-row flex-xl-column bg-light overflow-auto overflow-xl-visible rounded-top rounded-xl-top-0 rounded-xl-left">
              <div class="dropdown-content-body flex-1 border-bottom border-bottom-xl-0 py-2 py-xl-3">
                <div class="font-weight-semibold border-bottom d-none d-xl-block pb-2 mb-2">Navigation</div>
                <div class="nav flex-xl-column flex-nowrap justify-content-center wmin-xl-300">
									@if (auth()->user()->users_role_id==9)
									<a href="#menu_return" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0 active" data-toggle="tab">
                    <i class="icon-coins position-static pr-1 mr-2"></i>
                    E-Return
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
									@else
                  <a href="#sales_ar" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0 active" data-toggle="tab">
                    <i class="icon-coins position-static pr-1 mr-2"></i>
                    Sales A/R
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
                  <a href="#sync_sfa" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0" data-toggle="tab">
                    <i class="icon-database-refresh position-static pr-1 mr-2"></i>
                    Syncronize Data
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
                  <a href="#report" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0" data-toggle="tab">
                    <i class="icon-printer2 position-static pr-1 mr-2"></i>
                    Report
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
                  <a href="#tab_navbars" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0" data-toggle="tab">
                    <i class="icon-coins position-static pr-1 mr-2"></i>
                    Collector
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
                  <a href="#tab_navbars" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0" data-toggle="tab">
                    <i class="icon-database-refresh position-static pr-1 mr-2"></i>
                    GPS Compliance From SFA
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
                  <a href="#master" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0" data-toggle="tab">
                    <i class="icon-folder4 position-static pr-1 mr-2"></i>
                    Master
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
									<a href="#interfacing" class="list-group-item list-group-item-action rounded mr-2 mr-xl-0" data-toggle="tab">
                    <i class="icon-folder4 position-static pr-1 mr-2"></i>
                    Interfacing
                    <i class="icon-arrow-right8 position-static list-group-item-active-indicator d-none d-xl-inline-block ml-auto"></i>
                  </a>
									@endif
                </div>
              </div>
            </div>

            <div class="tab-content flex-xl-1">
              <div class="tab-pane dropdown-content-body dropdown-scrollable-xl fade {{ auth()->user()->users_role_id!=9 ? 'show active' : '' }}" id="sales_ar">
                <div class="row">
                  <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="font-weight-semibold border-bottom pb-2 mb-2">Sales A/R</div>
                    <a href="{{ route('backend.app.sales') }}" class="dropdown-item rounded">Sales Order</a>
                    <a href="#" class="dropdown-item rounded">Delivery Order</a>
                    <a href="#" class="dropdown-item rounded">Return</a>
                    <a href="#" class="dropdown-item rounded">A/R invoice</a>
                  </div>
                </div>
              </div>
							<div class="tab-pane dropdown-content-body dropdown-scrollable-xl fade {{ auth()->user()->users_role_id==9 ? 'show active' : '' }}"" id="menu_return">
                <div class="row">
                  <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="font-weight-semibold border-bottom pb-2 mb-2">Menu E-Return</div>
                    <a href="#" class="dropdown-item rounded">Approval</i></a>
                  </div>
                </div>
              </div>
              <div class="tab-pane dropdown-content-body dropdown-scrollable-xl fade" id="sync_sfa">
                <div class="row">
                  <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="font-weight-semibold border-bottom pb-2 mb-2">Syncronize Data From SFA</div>
                    <a href="{{ route('backend.sync.png') }}" class="dropdown-item rounded">SFA P&G</a>
                    <a href="{{ route('backend.sync.mix') }}" class="dropdown-item rounded">SFA MIX</a>
                    <a href="{{ route('backend.sync.taa') }}" class="dropdown-item rounded">SFA TAA</a>
                    <a href="#" class="dropdown-item rounded">VDIST <i>(Coming Soon)</i></a>
                  </div>
                </div>
              </div>
              <div class="tab-pane dropdown-content-body dropdown-scrollable-xl fade" id="report">
                <div class="row">
                  <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="font-weight-semibold border-bottom pb-2 mb-2">Logistik</div>
                    <a href="#" class="dropdown-item rounded">Packing List Globalan</a>
                    <a href="#" class="dropdown-item rounded">Daily Delivery By Sales</a>
                    <a href="#" class="dropdown-item rounded">Daily Delivery By Plat</a>
                    <a href="#" class="dropdown-item rounded">Rekap SO By Sales</a>
                    <a href="#" class="dropdown-item rounded">Rekap SO By Plat</a>
                    <a href="#" class="dropdown-item rounded">Rekap DO By Plat</a>
                  </div>
                </div>
              </div>
              <div class="tab-pane dropdown-content-body dropdown-scrollable-xl fade" id="master">
                <div class="row">
                  <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="font-weight-semibold border-bottom pb-2 mb-2">Master Data</div>
                    <a href="{{ route('backend.user') }}" class="dropdown-item rounded">Users Management</a>
                    <a href="#" class="dropdown-item rounded">API Listing URL</a>
                    <a href="{{ route('backend.master.uom_entry') }}" class="dropdown-item rounded">Uom Entry</a>
                    <a href="{{ route('backend.master.discount_program.lta') }}" class="dropdown-item rounded">Discount Program LTA</a>
                    <a href="{{ route('backend.master.discount_program.png') }}" class="dropdown-item rounded">Discount Program P&G </a>
                  </div>
                </div>
              </div>
							<div class="tab-pane dropdown-content-body dropdown-scrollable-xl fade" id="interfacing">
                <div class="row">
                  <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="font-weight-semibold border-bottom pb-2 mb-2">Interfacing</div>
                    <a href="#" class="dropdown-item rounded">RTDX IS</a>
										<a href="{{ route('backend.app.interfacing.storemaster') }}" class="dropdown-item rounded">RTDX STORE MASTER</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>

  <div class="d-flex flex-xl-1 justify-content-xl-end order-0 order-xl-1 pr-3">
    <ul class="navbar-nav navbar-nav-underline flex-row">		
      <li class="nav-item nav-item-dropdown-xl dropdown dropdown-user h-100">
        <a href="#" class="navbar-nav-link navbar-nav-link-toggler d-flex align-items-center h-100 dropdown-toggle" data-toggle="dropdown">
          <img src="{{ asset('assets/images/user.png') }}" class="rounded-circle mr-xl-2" height="38" alt="">
          <span class="d-none d-xl-block">{{ auth()->user()->name }}</span>
        </a>
  
        <div class="dropdown-menu dropdown-menu-right">
          <a href="{{ route('backend.history') }}" class="dropdown-item">
            <i class="icon-database-refresh"></i> History
          </a>
          <a href="{{ route('backend.logout') }}" class="dropdown-item">
            <i class="icon-switch2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </div>
</div>
<!-- /main navbar -->