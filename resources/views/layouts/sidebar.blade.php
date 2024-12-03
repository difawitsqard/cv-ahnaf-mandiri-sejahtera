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

               @hasrole('superadmin')

                   @if (request()->route('outlet'))
                       @php
                           $outletSlug = request()->route('outlet');
                       @endphp
                       <li>
                           <a href="{{ route('outlet.dashboard', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon"><i class="material-icons-outlined">home</i>
                               </div>
                               <div class="menu-title">Dashboard</div>
                           </a>
                       </li>
                       <li>
                           <a href="{{ route('outlet.order.create', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">shopping_cart</i>
                               </div>
                               <div class="menu-title" title="Buat Pesanan">Buat Pesanan</div>
                           </a>
                       </li>
                       <li>
                           <a href="{{ route('outlet.order.index', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">history</i>
                               </div>
                               <div class="menu-title" title="Riwayat Pesanan">Riwayat Pesanan</div>
                           </a>
                       </li>
                       <li class="menu-label">Menu</li>
                       <li>
                           <a href="{{ route('outlet.user.index', ['outlet' => $outletSlug]) }}">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">group</i>
                               </div>
                               <div class="menu-title" title="Manajemen Pengguna">Manajemen Pengguna</div>
                           </a>
                       </li>
                       <li>
                           <a href="#" class="has-arrow">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">menu_book</i>
                               </div>
                               <div class="menu-title">Manajemen Menu</div>
                           </a>
                           <ul>
                               <li>
                                   <a href="{{ route('outlet.menu.create', ['outlet' => $outletSlug]) }}">
                                       <i class="material-icons-outlined">arrow_right</i>
                                       Tambah Menu
                                   </a>
                               </li>
                               <li>
                                   <a href="{{ route('outlet.menu.index', ['outlet' => $outletSlug]) }}">
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
                               <div class="menu-title">Manajemen Stok</div>
                           </a>
                           <ul>
                               <li>
                                   <a href="{{ route('outlet.stock-item.create', ['outlet' => $outletSlug]) }}">
                                       <i class="material-icons-outlined">arrow_right</i>
                                       Tambah Item
                                   </a>
                               </li>
                               <li>
                                   <a href="{{ route('outlet.stock-item.index', ['outlet' => $outletSlug]) }}">
                                       <i class="material-icons-outlined">arrow_right</i>
                                       Daftar Item
                                   </a>
                               </li>
                           </ul>
                       </li>
                   @else
                       <li>
                           <a href="{{ route('outlet.index') }}">
                               <div class="parent-icon"><i class="material-icons-outlined">store</i>
                               </div>
                               <div class="menu-title">Manajemen Outlet</div>
                           </a>
                       </li>
                   @endif
               @endhasrole

               @hasrole('admin')
                   <li>
                       <a href="{{ route('admin.dashboard') }}">
                           <div class="parent-icon"><i class="material-icons-outlined">home</i>
                           </div>
                           <div class="menu-title">Dashboard</div>
                       </a>
                   </li>
                   <li>
                       <a href="{{ route('admin.order.create') }}">
                           <div class="parent-icon">
                               <i class="material-icons-outlined">shopping_cart</i>
                           </div>
                           <div class="menu-title" title="Buat Pesanan">Buat Pesanan</div>
                       </a>
                   </li>
                   <li>
                       <a href="{{ route('admin.order.index') }}">
                           <div class="parent-icon">
                               <i class="material-icons-outlined">history</i>
                           </div>
                           <div class="menu-title" title="Riwayat Pesanan">Riwayat Pesanan</div>
                       </a>
                   </li>
                   <li class="menu-label">Menu</li>
                   <li>
                       <a href="{{ route('admin.user.index') }}">
                           <div class="parent-icon">
                               <i class="material-icons-outlined">group</i>
                           </div>
                           <div class="menu-title" title="Manajemen Pengguna">Manajemen Pengguna</div>
                       </a>
                   </li>
                   <li>
                       <a href="#" class="has-arrow">
                           <div class="parent-icon">
                               <i class="material-icons-outlined">menu_book</i>
                           </div>
                           <div class="menu-title">Manajemen Menu</div>
                       </a>
                       <ul>
                           <li>
                               <a href="{{ route('admin.menu.create') }}">
                                   <i class="material-icons-outlined">arrow_right</i>
                                   Tambah Menu
                               </a>
                           </li>
                           <li>
                               <a href="{{ route('admin.menu.index') }}">
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
                           <div class="menu-title">Manajemen Stok</div>
                       </a>
                       <ul>
                           <li>
                               <a href="{{ route('admin.stock-item.create') }}">
                                   <i class="material-icons-outlined">arrow_right</i>
                                   Tambah Item
                               </a>
                           </li>
                           <li>
                               <a href="{{ route('admin.stock-item.index') }}">
                                   <i class="material-icons-outlined">arrow_right</i>
                                   Daftar Item
                               </a>
                           </li>
                       </ul>
                   </li>
               @endhasrole


               @hasrole('staff')
                   <li>
                       <a href="{{ route('staff.dashboard') }}">
                           <div class="parent-icon"><i class="material-icons-outlined">home</i>
                           </div>
                           <div class="menu-title">Dashboard</div>
                       </a>
                   </li>
                   <li>
                       <a href="{{ route('staff.order.create') }}">
                           <div class="parent-icon">
                               <i class="material-icons-outlined">shopping_cart</i>
                           </div>
                           <div class="menu-title" title="Buat Pesanan">Buat Pesanan</div>
                       </a>
                   </li>
                   <li>
                       <a href="{{ route('staff.order.index') }}">
                           <div class="parent-icon">
                               <i class="material-icons-outlined">history</i>
                           </div>
                           <div class="menu-title" title="Riwayat Pesanan">Riwayat Pesanan</div>
                       </a>
                   </li>
                   <li class="menu-label">Menu</li>
               @endhasrole


               <li class="menu-label">Pengaturan</li>
               @hasrole('superadmin')
                   @if (request()->route('outlet'))
                       <li>
                           <a href="{{ route('outlet.index') }}">
                               <div class="parent-icon"><i class="material-icons-outlined">store</i>
                               </div>
                               <div class="menu-title">Manajemen Outlet</div>
                           </a>
                       </li>
                   @endif
               @endhasrole

               @hasrole('superadmin|admin')
                   <li>
                       @if (request()->route('outlet') && auth()->user()->hasRole('superadmin'))
                           <a href="{{ route('outlet.unit.index', ['outlet' => request()->route('outlet')]) }}">
                           @else
                               <a href="{{ route('unit.index') }}">
                       @endif
                       <div class="parent-icon"><i class="material-icons-outlined">calculate</i>
                       </div>
                       <div class="menu-title">Satuan / Unit</div>
                       </a>
                   </li>
               @endhasrole

               @hasrole('superadmin')
                   <li>
                       @if (request()->route('outlet') && auth()->user()->hasRole('superadmin'))
                           <a href="{{ route('outlet.company-info.index', ['outlet' => request()->route('outlet')]) }}">
                           @else
                               <a href="{{ route('company-info.index') }}">
                       @endif
                       <div class="parent-icon"><i class="material-icons-outlined">apartment</i>
                       </div>
                       <div class="menu-title">Info Perusahaan</div>
                       </a>
                   </li>
               @endhasrole
           </ul>
           <!--end navigation-->
       </div>
   </aside>
   <!--end sidebar-->
