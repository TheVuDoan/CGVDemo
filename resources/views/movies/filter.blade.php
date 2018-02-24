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
