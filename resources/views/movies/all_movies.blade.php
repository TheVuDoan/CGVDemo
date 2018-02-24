@extends('layouts.sidebar')

@section('content')
<div class="content-wrapper">
    <div class="container">
        <h2>Tìm Kiếm Phim</h2>
        <p>Tìm theo tên, thể loại, quốc gia,..</p>  
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tên Phim</th>
                    <th>Thể Loại</th>
                    <th>Thời Lượng</th>
                    <th>Khởi Chiếu</th>
                    <th>Phụ Đề</th>
                    <th>Độ Tuổi</th>
                    <th>Đạo Diễn</th>
                    <th>Quốc Gia</th>
                    <th>Like</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($allmovies as $movie)
                <tr>
                    <td><a href="{{ url('admin/movieInfo/' . $movie->id) }}">{{ $movie->title }}</a></td>
                    <td>{{ $movie->genres }}</td>
                    <td>{{ $movie->length }} phút</td>
                    <td>{{ $movie->release_date }}</td>
                    <td>{{ $movie->subtitle }}</td>
                    <td>{{ $movie->rating }}</td>
                    <td>{{ $movie->director }}</td>
                    <td>{{ $movie->country }}</td>
                    <td>{{ $movie->count_like }} like(s)</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <p>Note that we start the search in tbody, to prevent filtering the table headers.</p>
    </div>
</div>

<script src="{{ asset('js/active_sidebar.js') }}"></script>
@endsection
