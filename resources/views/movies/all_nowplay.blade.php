@extends('layouts.sidebar')

@section('content')
<div class="content-wrapper">
    <div class="content movie-info-admin">
        <h2>Tìm Kiếm Phim</h2>
        <div class="row">
            <div class="col-md-6">
                <p>Tìm theo tên, thể loại, quốc gia,..</p>  
                <input class="form-control" id="myInput" type="text" placeholder="Search..">
                <br>
            </div>
            <div class="col-md-6">
                <select id="select-theater">
                    <option value="-1">Tất cả</option>
                    @foreach ($theater_name as $name)
                    <option value="{{ $name->id }}">{{ $name->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Poster</th>
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
                @foreach ($nowplay as $movie)
                <tr>
                    <td><a href="{{ url('admin/movieInfo/' . $movie->id) }}"><img class="img-allmovie" src="{{ $movie->url }}"></a></td>
                    <td><a href="{{ url('admin/movieInfo/' . $movie->id) }}">{{ $movie->title }}</a></td>
                    <td>{{ $movie->genres }}</td>
                    <td>{{ $movie->length }} phút</td>
                    <td>{{ $movie->release_date }}</td>
                    <td>{{ $movie->subtitle }}</td>
                    <td>{{ $movie->rating }}</td>
                    <td>{{ $movie->director }}</td>
                    <td>{{ $movie->country }}</td>
                    <td>{{ $movie->count_like }} like(s)</td>
                    <td><a href="{{ url('admin/movieInfo/' . $movie->id) }}"><button class="btn btn-success">UPDATE</button></a></td>
                    <td><a href=""><button class="btn btn-danger">DELETE</button></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/active_sidebar.js') }}"></script>
@endsection