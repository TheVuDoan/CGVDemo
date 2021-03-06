@extends('layouts.app')

@section('content')
@php $info = $seatmap[0] @endphp
<div class="container">
    <div class="seatmap">
        <div class="row seatmap-show">
            <div class="seatmap-mid inline-left">
                <div class="seatmap-screen">
                    ----------------------------MAN HINH-----------------------------
                </div>
                @for ($row = 1, $c = 'A'; $row <= (int) $info->row_num; $row++, $c++)
                <div class="seatmap-row">
                    @for ($col = 1; $col <= (int) $info->column_num; $col++)
                        @php $status = 'uncheck' @endphp
                        @foreach ($chosenSeat as $chosen)
                            @if ($c.$col == $chosen->chair_num)
                                @php $status = 'chosen' @endphp
                            @endif
                        @endforeach
                        @foreach ($mySeat as $seat)
                            @if ($c.$col == $seat->chair_num)
                                @php $status = 'myseat' @endphp
                            @endif
                        @endforeach
                    <div class="seat {{ $status }}" data-id1="{{ $c . $col }}" data-id2="{{ $info->id }}">
                        {{ $c . $col }}
                    </div>
                    @endfor
                </div>
                @endfor
            </div>
            <div class="seatmap-legend inline-right">
                <div class="seat-type-icon">
                    <span class="type-icon selected">
                        <span class="icon-box"></span>
                        <span>Đang chọn</span>
                    </span>
                    <span class="type-icon reserved">
                        <span class="icon-box"></span>
                        <span>Đã chọn</span>
                    </span>
                    <span class="type-icon change">
                        <span class="icon-box"></span>
                        <span>Ghế bạn đặt</span>
                    </span>
                    <span class="type-icon notavail">
                        <span class="icon-box"></span>
                        <span>Không thể chọn</span>
                    </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="row seatmap-bottom">
            <div class="process-left-seatmap inline-left">
                <a href="{{ url('theaters') }}">
                    <button>Previous</button>
                </a>
            </div>
            <div class="movie-cart-info inline-left">
                <div class="ticket-movie-image inline-block">
                    <img class="ticket-image" src="{{ '/img/' . $info->url }}">
                </div>
                <div class="ticket-movie-info inline-block">
                    <span class="ticket-movie-name display-block">
                        <label>Tên phim: </label>{{ $info->title }}
                    </span>
                    <span class="ticket-movie-type display-block">
                        <label>Định dạng: </label>{{ $info->type }}
                    </span>
                </div>
                <div class="ticket-movie-session inline-block">
                    <span class="movie-session-site display-block">
                        <label>Rạp chiếu: </label>{{ $info->name }}
                    </span>
                    <span class="movie-session-date display-block">
                        <label>Ngày chiếu: </label>{{ $info->show_date }}
                    </span>
                    <span class="movie-session-time display-block">
                        <label>Giờ chiếu: </label>{{ $info->show_time }}
                    </span>
                </div>
                <div class="selected-seat inline-block">
                    <div>
                        <label id="chair-num">Số ghế: </label>
                        @php $i = 0; $string = "" @endphp
                        @foreach ($mySeat as $seat)
                            @if ($i++ < count($mySeat) - 1)
                                <span class="inline-block">{{ $seat->chair_num . ', ' }}</span>
                            @else
                                <span class="inline-block">{{ $seat->chair_num }}</span>
                            @endif
                            @php $string = $string . $seat->chair_num . ' ' @endphp
                        @endforeach
                        <span class="movie-seats inline-block" data-id3="{{ $string }}"></span>
                    </div>
                    <div>
                        <label>Tổng tiền: </label>
                        <span id="total" data-id4 = "">{{ $nowBill }}</span>
                    </div>
                </div>
            </div>
            <button class="process-right-seatmap inline-left">Next</button>
            <div class="clear"></div>
        </div>
    </div>
</div>

<script src="{{ asset('js/seat_ticket.js') }}"></script>
@endsection