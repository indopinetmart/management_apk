@include('partials.header')

<div class="content-wrapper">
    <br>
    @include('partials.loader')
    <section class="content">
        <div class="container-fluid mt-2">


            <!---// INDIKATOR WIDGET----->
            <!---// INDIKATOR WIDGET----->
            <!---// INDIKATOR WIDGET----->

            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box bg-dark">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">CPU Traffic</span>
                            <span class="info-box-number">
                                10
                                <small>%</small>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Likes</span>
                            <span class="info-box-number">41,410</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Sales</span>
                            <span class="info-box-number">760</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">New Members</span>
                            <span class="info-box-number">2,000</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>

            <!---// END INDIKATOR WIDGET----->
            <!---// END INDIKATOR WIDGET----->
            <!---// END INDIKATOR WIDGET----->


            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center px-3">
                            <h3 class="card-title mb-0">Currency Now</h3>
                            <span id="current-date" class="text-muted" style="margin-left:auto;"></span>
                        </div>
                        <div class="card-body">
                            <!-- 1 Pi dalam USD -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                <p class="text-purple text">
                                    <i class="ion ion-ios-refresh-empty"><b>1 Pi</b></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                                    <span class="text-muted"><b>{{ $piUSD }}</b></span>
                                </p>
                            </div>

                            <!-- 1 USD dalam IDR -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                <p class="text-danger text">
                                    <i class="ion ion-ios-cart-outline"><b>1 $</b></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                                    <span class="text-muted"><b>{{ $piRate }}</b></span>
                                </p>
                            </div>

                            <!-- 1 Pi dalam IDR -->
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <p class="text-primary text">
                                    <i class="ion ion-ios-people-outline"><b>1 Pi</b></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                                    <span class="text-muted"><b>{{ $piInIDR }}</b></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </section>
</div>


@include('partials.script')
@include('admin.dashboard.script_dashboard')
