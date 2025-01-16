   <!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
       <div class="sidebar-header d-flex align-items-center justify-content-center">
           <div class="d-flex align-items-center">
               <div class="logo-icon">
                   <img src="{{ URL::asset('build/images/logo.png') }}" class="logo-img p-2"
                       alt="{{ getCompanyInfo()->name ?? 'This Company' }}">
               </div>
               <div class="logo-name ms-2 pe-4">
                   <h5 class="mb-0"
                       style="white-space: nowrap; overflow: hidden;max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                       {{ getCompanyInfo()->short_name ?? (getCompanyInfo()->name ?? 'This Company') }}
                   </h5>
               </div>
           </div>
           <div class="sidebar-close position-absolute" style="right: 10px;">
               <span class="material-icons-outlined">close</span>
           </div>
       </div>
       <div class="sidebar-nav">
           <!--navigation-->
           <ul class="metismenu" id="sidenav">

               @hasrole('superadmin|admin|staff')

                   @if (request()->route('outlet'))
                       @php
                           $outletSlug = request()->route('outlet');
                       @endphp
                       <li>
                           <a href="{{ roleBasedRoute('dashboard', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon"><i class="material-icons-outlined">home</i>
                               </div>
                               <div class="menu-title">Dashboard</div>
                           </a>
                       </li>
                       <li>
                           <a href="{{ roleBasedRoute('order.create', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">shopping_cart</i>
                               </div>
                               <div class="menu-title" title="Buat Pesanan">Buat Pesanan</div>
                           </a>
                       </li>
                       <li>
                           <a href="{{ roleBasedRoute('order.index', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">history</i>
                               </div>
                               <div class="menu-title" title="Riwayat Pesanan">Riwayat Pesanan</div>
                           </a>
                       </li>

                       <li class="menu-label">Menu Manajemen</li>

                       @hasrole('superadmin|admin')
                           <li>
                               <a href="{{ roleBasedRoute('user.index', ['outlet' => $outletSlug]) }}">
                                   <div class="parent-icon">
                                       <i class="material-icons-outlined">group</i>
                                   </div>
                                   <div class="menu-title" title="Pengguna">Pengguna</div>
                               </a>
                           </li>
                           <li>
                               <a href="#" class="has-arrow">
                                   <div class="parent-icon">
                                       <i class="material-icons-outlined">library_books</i>
                                   </div>
                                   <div class="menu-title">Menu </div>
                               </a>
                               <ul>
                                   <li>
                                       <a href="{{ roleBasedRoute('menu.create', ['outlet' => $outletSlug]) }}">
                                           <i class="material-icons-outlined">arrow_right</i>
                                           Tambah Menu
                                       </a>
                                   </li>
                                   <li>
                                       <a href="{{ roleBasedRoute('menu.index', ['outlet' => $outletSlug]) }}">
                                           <i class="material-icons-outlined">arrow_right</i>
                                           Daftar Menu
                                       </a>
                                   </li>
                               </ul>
                           </li>
                           <li>
                               <a href="#" class="has-arrow">
                                   <div class="parent-icon">
                                       <i class="material-icons-outlined">local_mall</i>
                                   </div>
                                   <div class="menu-title">Stok</div>
                               </a>
                               <ul>
                                   <li>
                                       <a href="{{ roleBasedRoute('stock-item.create', ['outlet' => $outletSlug]) }}">
                                           <i class="material-icons-outlined">arrow_right</i>
                                           Tambah Item
                                       </a>
                                   </li>
                                   <li>
                                       <a href="{{ roleBasedRoute('stock-item.index', ['outlet' => $outletSlug]) }}">
                                           <i class="material-icons-outlined">arrow_right</i>
                                           Daftar Item
                                       </a>
                                   </li>
                                   <li>
                                       <a href="{{ roleBasedRoute('stock-item-category.index', ['outlet' => $outletSlug]) }}">
                                           <i class="material-icons-outlined">arrow_right</i>
                                           Daftar Kategori
                                       </a>
                                   </li>
                               </ul>
                           </li>
                       @endhasrole

                       <li>
                           <a href="#" class="has-arrow">
                               <div class="parent-icon">
                                   <span class="material-icons-outlined">account_balance_wallet</span>
                               </div>
                               <div class="menu-title" title="Pengeluaran">Pengeluaran</div>
                           </a>
                           <ul>
                               <li>
                                   <a href="{{ roleBasedRoute('expense.create', ['outlet' => $outletSlug]) }}">
                                       <i class="material-icons-outlined">arrow_right</i>
                                       Tambah Pengeluaran
                                   </a>
                               </li>
                               <li>
                                   <a href="{{ roleBasedRoute('expense.index', ['outlet' => $outletSlug]) }}">
                                       <i class="material-icons-outlined">arrow_right</i>
                                       Daftar Pengeluaran
                                   </a>
                               </li>
                           </ul>
                       </li>

                       <li class="menu-label">Menu Laporan</li>
                       <li>
                           <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exportReportModal"
                               data-bs-title="@hasrole('staff')Penjualan Saya @else Pendapatan @endhasrole"
                               data-bs-action="{{ roleBasedRoute('order.export', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">description</i>
                               </div>
                               <div class="menu-title">
                                   @hasrole('staff')Penjualan Saya @else Pendapatan @endhasrole
                               </div>
                           </a>
                       </li>
                       <li>
                           <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exportReportModal"
                               data-bs-title="Laporan Pengelauran"
                               data-bs-action="{{ roleBasedRoute('expense.export', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">description</i>
                               </div>
                               <div class="menu-title">Pengelauran @hasrole('staff')
                                       Saya
                                   @endhasrole
                               </div>
                           </a>
                       </li>

                       @hasrole('superadmin|admin')
                           <li class="menu-label">Lainnya</li>
                           <li>
                               <a href="{{ roleBasedRoute('unit.index', ['outlet' => $outletSlug]) }}">
                                   <div class="parent-icon"><i class="material-icons-outlined">calculate</i>
                                   </div>
                                   <div class="menu-title">Satuan / Unit</div>
                               </a>
                           </li>
                       @endhasrole

                       <li class="menu-label">Pengaturan</li>
                       @if (request()->route('outlet'))
                           @hasrole('superadmin|admin')
                               <li>
                                   <a href="{{ roleBasedRoute('outlet.edit', ['outlet' => $outletSlug]) }}">
                                       <div class="parent-icon"><i class="material-icons-outlined">storefront</i>
                                       </div>
                                       <div class="menu-title">Pengaturan Outlet</div>
                                   </a>
                               </li>
                           @endhasrole

                           <li>
                               <a href="{{ roleBasedRoute('account-settings.index', ['outlet' => $outletSlug]) }}">
                                   <div class="parent-icon"><i class="material-icons-outlined">settings</i>
                                   </div>
                                   <div class="menu-title">Pengaturan Akun</div>
                               </a>
                           </li>
                       @endif

                       @hasrole('superadmin')
                           @if (request()->route('outlet'))
                               <li class="menu-label">Superadmin</li>
                               <li class="bg-grd-royal rounded-2">
                                   <a href="{{ route('outlet.index') }}" class="text-light">
                                       <div class="parent-icon"><i class="material-icons-outlined">storefront</i>
                                       </div>
                                       <div class="menu-title">Daftar Outlet</div>
                                   </a>
                               </li>
                           @endif
                       @endhasrole
                   @endif
               @endhasrole
           </ul>

           <!--end navigation-->
       </div>
   </aside>
   <!--end sidebar-->
