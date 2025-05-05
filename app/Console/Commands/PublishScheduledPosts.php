<?php

namespace App\Console\Commands;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish posts that are scheduled for publication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $posts = Post::where('is_published', false)
            ->whereNotNull('publish_date')
            ->where('publish_date', '<=', $now)
            ->get();

        $count = $posts->count();

        if ($count > 0) {
            foreach ($posts as $post) {
                $post->is_published = true;
                $post->save();
                $this->info("Post published title : {$post->title}");
            }

            $this->info("posts published count : {$count}");
        } else {
            $this->info('no posts published');
        }

        return 0;
    }
}
