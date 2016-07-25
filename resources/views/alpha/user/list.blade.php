@include('alpha.header')

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <section class="panel">
                <header class="panel-heading">
                    用户列表
                    <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
                </header>
                @foreach ($userList as $u)
                    <div class="col-lg-6">
                        <section class="panel">
                            <div class="user-heading alt green-bg" style="padding: 20px;">
                                <a href="#">
                                    <img alt="" src="{{ $u->avatar }}">
                                </a>
                                <h1 style="margin-top: 33px;color: #fff;">{{ $u->mobile }}</h1>
                            </div>
                            <div class="panel-body bio-graph-info">
                                <div class="row">
                                    <div class="bio-row">
                                        <p><span>昵称 :</span>{{ $u->nick_name }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>真实名称 : </span>{{ $u->true_name }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>积分 : </span>{{ $u->points }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>余额 : </span>{{ $u->money }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>性别 : </span>{{ $u->sex }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>注册类型 : </span>{{ $u->login_type }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>帐号 : </span>{{ $u->account }}</p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>创建时间 : </span>{{ $u->created_at }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                @endforeach
            </section>
        </div>
                    <div class="text-center">
                        {!! $pageHtml !!}
                    </div>

    </section>


</section>
<!--main content end-->


@include('alpha.moduls.warning')

@include('alpha.footer')
