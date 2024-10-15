   <!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
       <div class="sidebar-header">
           <div class="logo-icon">
               <img src="{{ URL::asset('build/images/logo.png') }}" class="logo-img" alt="{{ config('app.name') }}">
           </div>
           <div class="logo-name flex-grow-1">
               <h5 class="mb-0 text-uppercase">{{ config('app.name') }}</h5>
           </div>
           <div class="sidebar-close">
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
                           <a href="javascript:;" class="has-arrow">
                               <div class="parent-icon">
                                   <i class="material-icons-outlined">inventory_2</i>
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
               <li>
                   <a href="{{ route('unit.index') }}">
                       <div class="parent-icon"><i class="material-icons-outlined">calculate</i>
                       </div>
                       <div class="menu-title">Satuan / Unit</div>
                   </a>
               </li>
           </ul>
           <!--end navigation-->
       </div>
   </aside>
   <!--end sidebar-->
