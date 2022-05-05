<div class="navbar-default sidebar" role="navigation">
<div class="sidebar-nav navbar-collapse">
    <ul class="nav" id="side-menu">
        <li class="sidebar-search">
            <div class="input-group custom-search-form">
                <input type="text" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <!-- /input-group -->
        </li>
        <li>
            <a href="{!! url('/dashboard') !!}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
        </li>
        @if(Auth::user()->admin_level >= 3)
            <li>
                <a href="{!! url('/my/list') !!}"><i class="fa fa-edit fa-fw"></i> My List<span class="fa arrow"></span></a>
            </li>
            <li>
                <a href="{!! url('/hunters_activity') !!}"><i class="fa fa-edit fa-fw"></i>Hunters Activity</a>
            </li>
        @else
            <li>
                <a href="{!! url('/my/list') !!}"><i class="fa fa-dashboard fa-fw"></i> My List</a>
            </li>
        @endif

        <!-- <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Reports<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('/home') !!}">Advertisement</a>
                </li>
            </ul>
        </li> -->

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Members<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('/home') !!}">All</a>
                </li>
                <li>
                    <a href="{!! url('/member/expire/tobe-expire') !!}">To be Expire</a>
                </li>
                <li>
                    <!--a href="{ !! url('/member/expire/expired') !! }">Expired</a-->
                    <a href="{!! url('/payment/expire/hunters'); !!}">Expired +2 (Hunters)</a>
                </li>
                <li>
                    <a href="{!! url('/member/none') !!}">Non Members</a>
                </li>
                <li>
                    <a href="{!! url('/member/paa/agents') !!}">PAA Agents</a>
                </li>
                <li>
                    <a href="{!! url('/member/mpaa/agents') !!}">m-PAA Agents</a>
                </li>
                <li>
                    <a href="{!! url('/member/dashboard') !!}">Members Dashboard</a>
                </li>
                <li>
                    <a href="{!! url('/agent/activity/report') !!}">Agent Activity</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Payment<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('/payment/expire/tobe-expire') !!}">To Expire</a>
                </li>
                <li>
                    <a href="{!! url('/payment/expire/expired'); !!}">Expired</a>
                </li>
                <li>
                    <a href="{!! url('/payment/invoice/add'); !!}">Add Invoice</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Ads<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">

                <li>
                    <a href="{!! url('/ads/summary') !!}">Summary</a>
                </li>
                <li>
                    <a href="{!! url('/view/list/ads/by/customer') !!}">Search (Active Ads)</a>
                </li>
                <li>
                    <a href="{!! url('/ads/manage/to-be-removed') !!}">To Be Removed</a>
                </li>
                <li>
                    <a href="{!! url('/ads/offer/type') !!}">Offer Type</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Private Sellers<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('/sigle-ads/pending-payment') !!}">Pending Payment (Expired)</a>
                </li>
                <li>
                    <a href="{!! url('/sigle-ads/pending-payment-all') !!}">Pending Payments (Pvt Sellers)</a>
                </li>
                <li>
                    <a href="{!! url('/sigle-ads/pending-payment-upgrade') !!}">Pending Payments (Upgrades)</a>
                </li>
                <li>
                    <a href="{!! url('/ads/activate_commercial') !!}">Activate Paid Ads</a>
                </li>
                <li>
                    <a href="{!! url('/single-ads/active_ads') !!}">Active Ads</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>
        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Hunters<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('hunters/list') !!}"><i class="fa fa-edit fa-fw"></i> Hunters List</a>
                </li>
                <li>
                    <a href="{!! url('hunters/pending-payment-system') !!}"> <i class="fa fa-edit fa-fw"></i>Pending Payments (System) </a>
                </li>
                <li>
                    <a href="{!! url('hunters/pvt-sellers-expired') !!}"><i class="fa fa-edit fa-fw"></i>Pvt Sellers (Expired)</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Replace/ Delete<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('/check/mobile') !!}">Check Phone Number</a>
                </li>
                <li>
                    <a href="{!! url('/replace/mobile') !!}">Replace Phone Number</a>
                </li>
                <li>
                    <a href="{!! url('/delete/mobile') !!}">Delete Phone Number</a>
                </li>
                <li>
                    <a href="{!! url('/delete/user') !!}">Delete User Account</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> City <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('list.city') }}"><i class="fa fa-list-alt"></i> City List </a>
            </li>
        </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Condo <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('condo.list') }}"><i class="fa fa-list-alt"></i> Condo List </a>
            </li>
        </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Point of Interest <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('point.of.interest.list') }}"><i class="fa fa-list-alt"></i> POI List </a>
            </li>
        </ul>
            <!-- /.nav-second-level -->
        </li>

        {{-- 31: local namal
        31: server name
        51: server ragavan --}}

        @if(Auth::user()->email == 'namal@lpw.lk' || Auth::user()->email == 'ragavan@lpw.lk')
            <li>
                <a href="#"><i class="fa fa-edit fa-fw"></i> Articles<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{!! url('/articles') !!}">Articles</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
        @endif

{{--        <li>--}}
{{--            --}}

{{--            <!-- /.nav-second-level -->--}}
{{--        </li>--}}
        <!-- <li>
            <a href="{!! url('/view/list/ads/by/customer') !!}"><i class="fa fa-table fa-fw"></i> Active Ads</a>
        </li> -->
        <!-- For Customer view-->
        <li>
            <a href="#"><i class="fa fa-edit fa-fw"></i> Customer<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{!! url('customer/register') !!}">Register Customer</a>
                </li>
                <li>
                    <a href="{!! url('customer/upload') !!}">Customer Bulk Upload</a>
                </li>
                <li>
                    <a href="{!! url('customer/all') !!}">All</a>
                </li>
                <li>
                    <a href="{!! url('customer/archive') !!}">Archive</a>
                </li>
                <li>
                    <a href="{!! url('password/reset') !!}">Password Reset</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
