    <?php
        function ActivityEntry(string $entry)
        {
            DB::insert('insert into recent_activity (user_id,entry,created_at) values (?,?,?)', array(Auth::user()->id, $entry, now()));
        }