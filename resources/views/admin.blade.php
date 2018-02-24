
@extends('layouts.sidebar')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Trang chủ
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TỔNG SỐ VÉ ĐÃ BÁN</span>
              <span class="info-box-number">{{ $ticketNum }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TỔNG SỐ PHIM</span>
              <span class="info-box-number">{{ $movieNum }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TỔNG SỐ RẠP</span>
              <span class="info-box-number">{{ $theaterNum }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TỔNG SỐ NGƯỜI DÙNG</span>
              <span class="info-box-number">{{ $userNum }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->  

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <div class="box box-primary">
              <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>

              <h3 class="box-title">Tổng số vé bán được</h3>
    
              </div>
              <div class="box-body">
              <div id="bar-chart" style="height: 300px;"></div>
              </div>
          </div>
          
          <div class="box box-success">
              <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>

              <h3 class="box-title">Khoảng độ tuổi của người dùng</h3>
    
              </div>
              <div class="box-body">
              <div id="barChart2" style="height: 300px;"></div>
              </div>
          </div>
        </div>
        <!-- /.col -->

        <div class="col-md-4">
          <div class="box box-primary">
              <div class="box-header with-border">
                  <i class="fa fa-bar-chart-o"></i>

                  <h3 class="box-title">Thống kê theo khung giờ chiếu</h3>

                  <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
              </div>
              <div class="box-body">
                  <div id="donut-chart" style="height: 300px;"></div>
              </div>
          </div> 
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
    var data = {!! json_encode($theaters) !!};
    var data2 = [];
    for(var i=0;i<data.length;i++)
        data2.push([data[i].name,data[i].count]);
    var bar_data = {
      data : data2,
      color: '#3c8dbc'
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      }
    })
</script>
<script type="text/javascript">
    var data3 = {!! json_encode($schedules) !!};
    var count3 = [];
    for (var i=0;i<data3.length;i++) {
        if (data3[i].case != null) {
            count3[data3[i].case] = data3[i].count;
        } else {
            count3[5] = data3[i].count;
        }  
    }
    var donutData = [
      { label: '8h-12h', data: count3[1], color: '#00a65a' },
      { label: '12h-16h', data: count3[2], color: '#f39c12' },
      { label: '16h-20h', data: count3[3], color: '#00c0ef' },
      { label: '20h-24h', data: count3[4], color: '#3c8dbc' },
      { label: 'Khác', data: count3[5], color: '#d2d6de' }
    ]
    $.plot('#donut-chart', donutData, {
      series: {
        pie: {
          show       : true,
          radius     : 1,
          innerRadius: 0.5,
          label      : {
            show     : true,
            radius   : 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    });
    function labelFormatter(label, series) {
        return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
        + label
        + '<br>'
        + Math.round(series.percent) + '%</div>'
    }
</script>
<script type="text/javascript">
    var data4 = {!! json_encode($users) !!};
    var count4 = [];
    for(var i=0;i<data4.length;i++)
        count4[data4[i].case] = data4[i].count;
    data5 = [['Dưới 13',count4[1]],['13 ~ 18',count4[2]],['18 ~ 30',count4[3]],['30 ~ 50',count4[4]],['Trên 50',count4[5]]];
    console.log(data5);
    var bar_data2 = {
      data : data5,
      color: '#29BF12'
    }
    console.log(bar_data2);
    $.plot('#barChart2', [bar_data2], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      }
    })
</script>
@endsection