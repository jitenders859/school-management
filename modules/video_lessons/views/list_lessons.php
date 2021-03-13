<section class="section-a">
    <h1><?= $headline ?></h1>

    <div class="section-intro" bp='grid'>
        <div bp="9"><?= nl2br($introduction) ?></div>
        <div bp="3"><img src="<?= $pic_path?>" alt="<?= $title ?>"></div>
    </div>

    <div class="lessons-list">
        <?php
        $attributes['class'] =  'button';
        foreach($video_lessons as $video_lesson) {
            $target_url = BASE_URL.'video_lessons/learn/'.$video_lesson->code;
            $video_id = $video_lesson->id;
            if(in_array($video_id, $watched_vids)) {
                $picture_name = 'video_watched.png';
            } else {
                $picture_name = 'video_player.png';
            }
            ?>
            <div>
                <div><a href="<?= $target_url ?>"><img src="<?= BASE_URL ?>images/<?= $picture_name ?>" alt="Go to lesson"></a></div>
                <div>
                    <div class="lesson-count">Lesson <?= $video_lesson->priority; ?></div>
                    <h4><?= anchor($target_url, $video_lesson->title)  ?></h4>
                    <div><?= nl2br($video_lesson->video_teaser) ?></div>
                    <div bp="text-right" ></i> <?= anchor($target_url, '<i class="fa fa-film"></i> Go to Lesson', $attributes) ?></div>
                </div>
            </div>
            <?php
        } ?>
    </div>
</section>

