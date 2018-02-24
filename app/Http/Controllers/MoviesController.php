<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller {
    
    protected function nowplay() {
        $pageTitle = "Phim Đang Chiếu";
        $movieObj = new Movie();
        $movies = $movieObj->nowPlaying(30);
        return view('movies.nowplay', [
            'pageTitle' => $pageTitle,
            'movies' => $movies,
            'movieObj' => $movieObj
        ]);
    }

    protected function comesoon() {
        $pageTitle = "Phim Sắp Chiếu";
        $movieObj = new Movie();
        $movies = $movieObj->commingSoon(30);
        return view('movies.comesoon', [
            'pageTitle' => $pageTitle,
            'movies' => $movies,
            'movieObj' => $movieObj
        ]);
    }

    protected function displayLiked() {
        $movies = DB::select('SELECT movie.* FROM movies INNER JOIN likes WHERE user_id = ?', [Auth::id()]);
        return $movies;
    }

    protected function like() {
        $movie_id = $_POST['movie_id'];
        DB::insert('INSERT INTO likes(movie_id, user_id) VALUES (?, ?)', [$movie_id, Auth::id()]);
    }

    protected function unlike() {
        $movie_id = $_POST['movie_id'];
        DB::insert('DELETE FROM likes WHERE movie_id=? AND user_id=?', [$movie_id, Auth::id()]);
    }

    protected function adminNowPlay() { //adminNowplay
        $nowplay = DB::select("WITH ticketnum AS
                                    (SELECT movie_id, count(tickets.id) AS count_ticket
                                    FROM tickets INNER JOIN schedules
                                    ON tickets.schedule_id = schedules.id
                                    GROUP BY movie_id),
                                 likenum AS
                                    (SELECT likes.movie_id, count(likes.id) AS count_like
                                    FROM likes
                                    GROUP BY movie_id)
                        SELECT DISTINCT m.*, count_like, count_ticket
                        FROM movies m
                        LEFT JOIN ticketnum ON m.id = ticketnum.movie_id
                        LEFT JOIN likenum ON m.id = likenum.movie_id 
                        WHERE ?::date >= release_date::date 
                        AND 14 >= (select ?::date - release_date::date from movies where movies.id = m.id)
                        AND status = 1
                        GROUP BY m.id, count_ticket, count_like
                        ORDER BY count_ticket DESC NULLS LAST, count_like DESC NULLS LAST", [config('constant.today'), config('constant.today')]);
        $theater_name = DB::select('SELECT theaters.id, theaters.name FROM theaters where status = 1');
        return view('movies.admin_nowplay', [
            'nowplay' => $nowplay,
            'theater_name' => $theater_name
        ]);
    }

    protected function adminComeSoon() {
        $comesoon = DB::select('WITH likenum AS
                            (SELECT likes.movie_id, count(likes.id) AS count_like
                            FROM likes
                            GROUP BY movie_id)
                    SELECT m.*, count_like
                    FROM movies m
                    LEFT JOIN likenum ON m.id = likenum.movie_id
                    WHERE release_date::date > ?::date
                    AND 14 >= (select release_date::date - ?::date from movies where movies.id = m.id)
                    AND status = 1
                    GROUP BY m.id, count_like
                    ORDER BY count_like DESC NULLS LAST', [config('constant.today'), config('constant.today')]);
        $theater_name = DB::select('SELECT theaters.id, theaters.name FROM theaters');
        return view('movies.admin_comesoon', [
            'comesoon' => $comesoon,
            'theater_name' => $theater_name
        ]);
    }

    protected function adminAllMovies() {
        $allmovies = DB::select("WITH ticketnum AS
                                    (SELECT movie_id, count(tickets.id) AS count_ticket
                                    FROM tickets INNER JOIN schedules
                                    ON tickets.schedule_id = schedules.id
                                    GROUP BY movie_id),
                                 likenum AS
                                    (SELECT likes.movie_id, count(likes.id) AS count_like
                                    FROM likes
                                    GROUP BY movie_id)
                        SELECT m.*, count_like, count_ticket
                        FROM movies m
                        LEFT JOIN ticketnum ON m.id = ticketnum.movie_id
                        LEFT JOIN likenum ON m.id = likenum.movie_id 
                        WHERE status = 1
                        GROUP BY m.id, count_ticket, count_like
                        ORDER BY count_ticket DESC NULLS LAST, count_like DESC NULLS LAST");
        return view('movies.admin_allmovies', [
            'allmovies' => $allmovies
        ]);
    }

    protected function adminInfo($movie_id) {
        $movie = DB::select('SELECT * FROM movies WHERE id = ?', [$movie_id]);
        return view('movies.admin_info', [
            'movie' => $movie
        ]);
    }

    protected function adminUpdate(Request $request) {
        DB::update('UPDATE movies '
                . 'SET title = ?, release_date = ?, genres = ?, score = ?, director = ?, '
                . 'country = ?, length = ?, subtitle = ?, rating = ? '
                . 'WHERE id = ?', [$request->title, $request->release_date, $request->genres, $request->score,
            $request->director, $request->country, $request->length, $request->subtitle, $request->rating, $request->id]);
        return redirect('/admin');
    }

    protected function filterNowPlay() {
        $theater_id = (int) $_POST['theater_id'];
        if ($theater_id != -1) {
            $movies = DB::select('WITH ticketnum AS
                                    (SELECT movie_id, theater_id, count(tickets.id) AS count_ticket
                                    FROM tickets INNER JOIN schedules
                                    ON tickets.schedule_id = schedules.id
                                    GROUP BY movie_id, theater_id),
                                likenum as
                                   (SELECT likes.movie_id, theater_id, count(likes.id) AS count_like
                                   FROM likes, schedules
                                   WHERE likes.movie_id = schedules.movie_id
                                   GROUP BY likes.movie_id, theater_id)
                        SELECT distinct movies.*, count_like, count_ticket
                        FROM movies 
                        LEFT JOIN ticketnum ON movies.id = ticketnum.movie_id
                        LEFT JOIN likenum ON movies.id = likenum.movie_id
                        WHERE ticketnum.theater_id = ?
                        AND status = 1
                        GROUP BY movies.id, ticketnum.theater_id, count_ticket, count_like
                        ORDER BY count_ticket DESC, count_like DESC', [$theater_id]);
        } else {
            $movies = DB::select("WITH ticketnum AS
                                    (SELECT movie_id, count(tickets.id) AS count_ticket
                                    FROM tickets INNER JOIN schedules
                                    ON tickets.schedule_id = schedules.id
                                    GROUP BY movie_id),
                                 likenum AS
                                    (SELECT likes.movie_id, count(likes.id) AS count_like
                                    FROM likes
                                    GROUP BY movie_id)
                        SELECT m.*, count_like, count_ticket
                        FROM movies m
                        LEFT JOIN ticketnum ON m.id = ticketnum.movie_id
                        LEFT JOIN likenum ON m.id = likenum.movie_id 
                        WHERE ?::date >= release_date::date 
                        AND 14 >= (select ?::date - release_date::date from movies where movies.id = m.id)
                        AND status = 1
                        GROUP BY m.id, count_ticket, count_like
                        ORDER BY count_ticket DESC NULLS LAST, count_like DESC NULLS LAST", [config('constant.today'), config('constant.today')]);
        }
        return view('movies.admin_filter', [
            'movies' => $movies
        ]);
    }

    protected function filterComeSoon() {
        $theater_id = (int) $_POST['theater_id'];
        if ($theater_id != -1) {
            $movies = DB::select('WITH likenum AS
                                (SELECT likes.movie_id, theater_id, count(likes.id) AS count_like
                                FROM likes, schedules
                                WHERE likes.movie_id = schedules.movie_id
                                GROUP BY likes.movie_id, theater_id)
                        SELECT m.*, likenum.theater_id, count_like
                        FROM movies m
                        LEFT JOIN likenum ON m.id = likenum.movie_id
                        WHERE likenum.theater_id = ?
                        AND release_date::date > ?::date
                        AND 14 >= (select release_date::date - ?::date from movies where movies.id = m.id)
                        AND status = 1
                        GROUP BY m.id, likenum.theater_id, count_like
                        ORDER BY count_like DESC NULLS LAST', [$theater_id, config('constant.today'), config('constant.today')]);
        } else {
            $movies = DB::select('WITH likenum AS
                            (SELECT likes.movie_id, count(likes.id) AS count_like
                            FROM likes
                            GROUP BY movie_id)
                    SELECT m.*, count_like
                    FROM movies m
                    LEFT JOIN likenum ON m.id = likenum.movie_id
                    WHERE release_date::date > ?::date
                    AND 14 >= (select release_date::date - ?::date from movies where movies.id = m.id)
                    AND status = 1
                    GROUP BY m.id, count_like
                    ORDER BY count_like DESC NULLS LAST', [config('constant.today'), config('constant.today')]);
        }
        return view('movies.admin_filter', [
            'movies' => $movies
        ]);
    }

    protected function adminDelete() {
        $movie_id = $_POST['movie_id'];
        DB::delete('UPDATE movies SET status = 0 WHERE id = ?', [$movie_id]);
    }

    protected function addMovie() {
        return view('movies.adminAddMovie');
    }

    protected function add(Request $request) {
        if ($request->hasFile('file')) {
            $file = $request->file;
            $file->move('img', $file->getClientOriginalName());

            DB::insert('INSERT INTO movies (title, score, director, country, release_date, length, subtitle, genres, rating, url, status) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)', [$request->title, $request->score, $request->director, $request->country,
                $request->release_date, $request->length, $request->subtitle, $request->genres, $request->rating,
                $file->getClientOriginalName(), 1]);
        }
        return redirect('/admin');
    }

}
