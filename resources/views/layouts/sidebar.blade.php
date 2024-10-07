   <!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div class="logo-name flex-grow-1 text-center">
        <h5 class="mb-0">{{ config('app.name') }}</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">
        <!--navigation-->
        <ul class="metismenu" id="sidenav">
          <li>
            <a href="javascript:;" class="has-arrow">
              <div class="parent-icon"><i class="material-icons-outlined">home</i>
              </div>
              <div class="menu-title">Dashboard</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/index') }}"><i class="material-icons-outlined">arrow_right</i>Analysis</a>
              </li>
              <li><a href="{{ url('demo/index2') }}"><i class="material-icons-outlined">arrow_right</i>eCommerce</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="javascript:;" class="has-arrow">
              <div class="parent-icon"><i class="material-icons-outlined">widgets</i>
              </div>
              <div class="menu-title">Widgets</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/widgets-data') }}"><i class="material-icons-outlined">arrow_right</i>Data</a>
              </li>
              <li><a href="{{ url('demo/widgets-static') }}"><i class="material-icons-outlined">arrow_right</i>Static</a>
              </li>
            </ul>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">apps</i>
              </div>
              <div class="menu-title">Apps</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/app-emailbox') }}"><i class="material-icons-outlined">arrow_right</i>Email Box</a>
              </li>
              <li><a href="{{ url('demo/app-emailread') }}"><i class="material-icons-outlined">arrow_right</i>Email Read</a>
              </li>
              <li><a href="{{ url('demo/app-chat-box') }}"><i class="material-icons-outlined">arrow_right</i>Chat</a>
              </li>
              <li><a href="{{ url('demo/app-fullcalender') }}"><i class="material-icons-outlined">arrow_right</i>Calendar</a>
              </li>
              <li><a href="{{ url('demo/app-to-do') }}"><i class="material-icons-outlined">arrow_right</i>To do</a>
              </li>
              <li><a href="{{ url('demo/app-invoice') }}"><i class="material-icons-outlined">arrow_right</i>Invoice</a>
              </li>
            </ul>
          </li>
          <li class="menu-label">UI Elements</li>
          <li>
            <a href="{{ url('demo/cards') }}">
              <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i>
              </div>
              <div class="menu-title">Cards</div>
            </a>
          </li>
          
          <li>
            <a href="javascript:;" class="has-arrow">
              <div class="parent-icon"><i class="material-icons-outlined">shopping_bag</i>
              </div>
              <div class="menu-title">eCommerce</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/ecommerce-add-product') }}"><i class="material-icons-outlined">arrow_right</i>Add Product</a>
              </li>
              <li><a href="{{ url('demo/ecommerce-products') }}"><i class="material-icons-outlined">arrow_right</i>Products</a>
              </li>
              <li><a href="{{ url('demo/ecommerce-customers') }}"><i class="material-icons-outlined">arrow_right</i>Customers</a>
              </li>
              <li><a href="{{ url('demo/ecommerce-customer-details') }}"><i class="material-icons-outlined">arrow_right</i>Customer Details</a>
              </li>
              <li><a href="{{ url('demo/ecommerce-orders') }}"><i class="material-icons-outlined">arrow_right</i>Orders</a>
              </li>
              <li><a href="{{ url('demo/ecommerce-order-details') }}"><i class="material-icons-outlined">arrow_right</i>Order Details</a>
              </li>
            </ul>     
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">card_giftcard</i>
              </div>
              <div class="menu-title">Components</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/component-alerts') }}"><i class="material-icons-outlined">arrow_right</i>Alerts</a>
              </li>
              <li><a href="{{ url('demo/component-accordions') }}"><i class="material-icons-outlined">arrow_right</i>Accordions</a>
              </li>
              <li><a href="{{ url('demo/component-badges') }}"><i class="material-icons-outlined">arrow_right</i>Badges</a>
              </li>
              <li><a href="{{ url('demo/component-buttons') }}"><i class="material-icons-outlined">arrow_right</i>Buttons</a>
              </li>
              <li><a href="{{ url('demo/component-carousels') }}"><i class="material-icons-outlined">arrow_right</i>Carousels</a>
              </li>
              <li><a href="{{ url('demo/component-media-object') }}"><i class="material-icons-outlined">arrow_right</i>Media
                  Objects</a>
              </li>
              <li><a href="{{ url('demo/component-modals') }}"><i class="material-icons-outlined">arrow_right</i>Modals</a>
              </li>
              <li><a href="{{ url('demo/component-navs-tabs') }}"><i class="material-icons-outlined">arrow_right</i>Navs & Tabs</a>
              </li>
              <li><a href="{{ url('demo/component-navbar') }}"><i class="material-icons-outlined">arrow_right</i>Navbar</a>
              </li>
              <li><a href="{{ url('demo/component-paginations') }}"><i class="material-icons-outlined">arrow_right</i>Pagination</a>
              </li>
              <li><a href="{{ url('demo/component-popovers-tooltips') }}"><i class="material-icons-outlined">arrow_right</i>Popovers
                  & Tooltips</a>    
              </li>
              <li><a href="{{ url('demo/component-progress-bars') }}"><i class="material-icons-outlined">arrow_right</i>Progress</a>
              </li>
              <li><a href="{{ url('demo/component-spinners') }}"><i class="material-icons-outlined">arrow_right</i>Spinners</a>
              </li>
              <li><a href="{{ url('demo/component-notifications') }}"><i
                    class="material-icons-outlined">arrow_right</i>Notifications</a>
              </li>
              <li><a href="{{ url('demo/component-avtars-chips') }}"><i class="material-icons-outlined">arrow_right</i>Avatrs &
                  Chips</a>
              </li>
              <li><a href="{{ url('demo/component-typography') }}"><i class="material-icons-outlined">arrow_right</i>Typography</a>
               </li>
               <li><a href="{{ url('demo/component-text-utilities') }}"><i class="material-icons-outlined">arrow_right</i>Utilities</a>
               </li>
            </ul>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">view_agenda</i>
              </div>
              <div class="menu-title">Icons</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/icons-line-icons') }}"><i class="material-icons-outlined">arrow_right</i>Line Icons</a>
              </li>
              <li><a href="{{ url('demo/icons-boxicons') }}"><i class="material-icons-outlined">arrow_right</i>Boxicons</a>
              </li>
              <li><a href="{{ url('demo/icons-feather-icons') }}"><i class="material-icons-outlined">arrow_right</i>Feather
                  Icons</a>
              </li>
            </ul>
          </li>
          <li class="menu-label">Forms & Tables</li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">toc</i>
              </div>
              <div class="menu-title">Forms</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/form-elements') }}"><i class="material-icons-outlined">arrow_right</i>Form Elements</a>
              </li>
              <li><a href="{{ url('demo/form-input-group') }}"><i class="material-icons-outlined">arrow_right</i>Input Groups</a>
              </li>
              <li><a href="{{ url('demo/form-radios-and-checkboxes') }}"><i class="material-icons-outlined">arrow_right</i>Radios &
                  Checkboxes</a>
              </li>
              <li><a href="{{ url('demo/form-layouts') }}"><i class="material-icons-outlined">arrow_right</i>Forms Layouts</a>
              </li>
              <li><a href="{{ url('demo/form-validations') }}"><i class="material-icons-outlined">arrow_right</i>Form Validation</a>
              </li>
              <li><a href="{{ url('demo/form-wizard') }}"><i class="material-icons-outlined">arrow_right</i>Form Wizard</a>
              </li>
              <li><a href="{{ url('demo/form-file-upload') }}"><i class="material-icons-outlined">arrow_right</i>File Upload</a>
              </li>
              <li><a href="{{ url('demo/form-date-time-pickes') }}"><i class="material-icons-outlined">arrow_right</i>Date
                  Pickers</a>
              </li>
              <li><a href="{{ url('demo/form-select2') }}"><i class="material-icons-outlined">arrow_right</i>Select2</a>
              </li>
              <li><a href="{{ url('demo/form-repeater') }}"><i class="material-icons-outlined">arrow_right</i>Form Repeater</a>
              </li>
            </ul>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">api</i>
              </div>
              <div class="menu-title">Tables</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/table-basic-table') }}"><i class="material-icons-outlined">arrow_right</i>Basic Table</a>
              </li>
              <li><a href="{{ url('demo/table-datatable') }}"><i class="material-icons-outlined">arrow_right</i>Data Table</a>
              </li>
            </ul>
          </li>
          <li class="menu-label">Pages</li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">lock</i>
              </div>
              <div class="menu-title">Authentication</div>
            </a>
            <ul>
              <li><a class="has-arrow" href="javascript:;"><i class="material-icons-outlined">arrow_right</i>Basic</a>
                <ul>
                  <li><a href="{{ url('demo/auth-basic-login') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Login</a></li>
                  <li><a href="{{ url('demo/auth-basic-register') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Register</a></li>
                  <li><a href="{{ url('demo/auth-basic-forgot-password') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Forgot Password</a></li>
                  <li><a href="{{ url('demo/auth-basic-reset-password') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Reset Password</a></li>
                </ul>
              </li>
              <li><a class="has-arrow" href="javascript:;"><i class="material-icons-outlined">arrow_right</i>Cover</a>
                <ul>
                  <li><a href="{{ url('demo/auth-cover-login') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Login</a></li>
                  <li><a href="{{ url('demo/auth-cover-register') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Register</a></li>
                  <li><a href="{{ url('demo/auth-cover-forgot-password') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Forgot Password</a></li>
                  <li><a href="{{ url('demo/auth-cover-reset-password') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Reset Password</a></li>
                </ul>
              </li>
              <li><a class="has-arrow" href="javascript:;"><i class="material-icons-outlined">arrow_right</i>Boxed</a>
                  <ul>
                    <li><a href="{{ url('demo/auth-boxed-login') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Login</a></li>
                    <li><a href="{{ url('demo/auth-boxed-register') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Register</a></li>
                    <li><a href="{{ url('demo/auth-boxed-forgot-password') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Forgot Password</a></li>
                    <li><a href="{{ url('demo/auth-boxed-reset-password') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>Reset Password</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <li>
            <a href="{{ url('demo/user-profile') }}">
              <div class="parent-icon"><i class="material-icons-outlined">person</i>
              </div>
              <div class="menu-title">User Profile</div>
            </a>
          </li>
          <li>
            <a href="{{ url('demo/timeline') }}">
              <div class="parent-icon"><i class="material-icons-outlined">join_right</i>
              </div>
              <div class="menu-title">Timeline</div>
            </a>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">report_problem</i>
              </div>
              <div class="menu-title">Pages</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/pages-error-404') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>404
                  Error</a>
              </li>
              <li><a href="{{ url('demo/pages-error-505') }}" target="_blank"><i class="material-icons-outlined">arrow_right</i>505
                  Error</a>
              </li>
              <li><a href="{{ url('demo/pages-coming-soon') }}" target="_blank"><i
                    class="material-icons-outlined">arrow_right</i>Coming Soon</a>
              </li>
              <li><a href="{{ url('demo/pages-starter-page') }}" target="_blank"><i
                    class="material-icons-outlined">arrow_right</i>Blank Page</a> 
              </li>
            </ul>
          </li>
          <li>
            <a href="{{ url('demo/faq') }}">
              <div class="parent-icon"><i class="material-icons-outlined">help_outline</i>
              </div>
              <div class="menu-title">FAQ</div>
            </a>
          </li>
          <li>
            <a href="{{ url('demo/pricing-table') }}">
              <div class="parent-icon"><i class="material-icons-outlined">sports_football</i>
              </div>
              <div class="menu-title">Pricing</div>
            </a>
          </li>
          <li class="menu-label">Charts & Maps</li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">fitbit</i>
              </div>
              <div class="menu-title">Charts</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/charts-apex-chart') }}"><i class="material-icons-outlined">arrow_right</i>Apex</a>
              </li>
              <li><a href="{{ url('demo/charts-chartjs') }}"><i class="material-icons-outlined">arrow_right</i>Chartjs</a>
              </li>   
            </ul>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">sports_football</i>
              </div>
              <div class="menu-title">Maps</div>
            </a>
            <ul>
              <li><a href="{{ url('demo/map-google-maps') }}"><i class="material-icons-outlined">arrow_right</i>Google Maps</a>
              </li>
              <li><a href="{{ url('demo/map-vector-maps') }}"><i class="material-icons-outlined">arrow_right</i>Vector Maps</a>
              </li>
            </ul>
          </li>
          <li class="menu-label">Others</li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">face_5</i>
              </div>
              <div class="menu-title">Menu Levels</div>
            </a>
            <ul>
              <li><a class="has-arrow" href="javascript:;"><i class="material-icons-outlined">arrow_right</i>Level
                  One</a>
                <ul>
                  <li><a class="has-arrow" href="javascript:;"><i class="material-icons-outlined">arrow_right</i>Level
                      Two</a>
                    <ul>
                      <li><a href="javascript:;"><i class="material-icons-outlined">arrow_right</i>Level Three</a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li>
            <a href="javascript:void(0);">
              <div class="parent-icon"><i class="material-icons-outlined">description</i>
              </div>
              <div class="menu-title">Documentation</div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0);">
              <div class="parent-icon"><i class="material-icons-outlined">support</i>
              </div>
              <div class="menu-title">Support</div>
            </a>
          </li>
         </ul>
        <!--end navigation-->
    </div>
  </aside>
<!--end sidebar-->