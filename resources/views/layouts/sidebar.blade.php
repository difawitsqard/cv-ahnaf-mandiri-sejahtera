   <!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center">
            <div class="logo-icon">
                <img src="{{ URL::asset('build/images/logo.png') }}" class="logo-img p-2" alt="{{ config('app.name') }}">
            </div>
            <div class="logo-name ms-2 pe-4">
                <h5 class="mb-0" style="white-space: nowrap; overflow: hidden;max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ config('app.name') }}
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
                           <a href="javascript:;" class="has-arrow">
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
