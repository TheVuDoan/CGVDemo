<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Movie extends Model
{
    public function checkLike($movieId) 
    {
        $logged = \Auth::user();
        if (is_null($logged)) {
            return FALSE;
        }
        $check = \DB::table('likes')->where('user_id', '=', $logged->id)
                ->where('movie_id', '=', $movieId)
                ->first();
        if (is_null($check)) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function nowPlaying($limit) {
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
                        ORDER BY count_ticket DESC NULLS LAST, count_like DESC NULLS LAST
                        LIMIT ?", [config('constant.today'), config('constant.today'), $limit]);
        return $movies;
    }
    
    public function commingSoon($limit) {
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
                    ORDER BY count_like DESC NULLS LAST
                    LIMIT ?', [config('constant.today'), config('constant.today'), $limit]);
        return $movies;
    }
}
