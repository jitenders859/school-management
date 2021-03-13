<?php
    function get_percent_done($watched_vids, $section_vids) {
        $num_section_vids = count($section_vids);

        if($num_section_vids >0) {
            $watched_count = 0;

            foreach($watched_vids as $watched_video_id) {

                if(in_array($watched_video_id, $section_vids)) {
                    $watched_count++;
                }
            }

            $percent_done = ($watched_count/$num_section_vids) * 100;
            $percent_done = number_format($percent_done, 0);
        } else {
            $percent_done = 0;
        }

        return $percent_done;
    }

    function get_section_vids($target_section_id, $published_vids) {
        // return an array of videos that belong to a particular section
        $section_vids = []; // prevent errors

        foreach($published_vids as $published_vid) {
            if ($target_section_id == $published_vid->section_id) {
                $section_vids[] = $published_vid->id;
            }

            return $section_vids;
        }
    }
 ?>

<section class="section-a">
    <h1>Dashboard</h1>
    <h3>Today News</h3>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores aliquam deserunt similique a obcaecati ut quae magnam, qui consequatur minus accusantium, numquam distinctio facilis culpa! Vero cumque ullam perferendis, aliquam consequatur aspernatur unde?</p>
</section>

<section class="section-b">
    <h3>Training Material</h3>
    <div bp="grid 6@sm 4@md 3@lg" class="training-grid">
        <?php
        $i = 0;
        $attributes['class'] = 'button';
        foreach($training_modules as $training_module) {
            $i++;
            $pic_path = BASE_URL.'sections_pics/'.$training_module->id.'/'.$training_module->picture;
            $target_url = BASE_URL.'video_lessons/list_lessons/'.$training_module->code;
               $section_id = $training_module->id;
               $section_vids = get_section_vids($section_id, $published_vids);
            $percent_done = get_percent_done($watched_vids, $section_vids);
              ?>
                    <div>
                        <h4><?= anchor( $target_url , 'Module '.$i.': '.$training_module->title) ?></h4>
                        <div><a href="<?= $target_url ?>"><img src="<?=$pic_path ?>" alt="<?= $training_module->title ?>"></a></div>
                        <h5><?= $percent_done ?>% complete</h5>
                        <div><?= anchor($target_url , 'Go To Training', $attributes) ?></div>
                    </div>
                <?php
        } ?>
    </div>
</section>

<section class="section-c">
    <h3>Projects</h3>
    <div bp="grid 6@sm 4@md 3@lg" class="training-grid">
    <?php
        foreach($projects as $project) {
            $pic_path = BASE_URL.'sections_pics/'.$project->id.'/'.$project->picture;
            $target_url = BASE_URL.'video_lessons/list_lessons/'.$project->code;
            $section_id = $project->id;
            $section_vids = get_section_vids($section_id, $published_vids);
            $percent_done = get_percent_done($watched_vids, $section_vids);

            ?>
                    <div>
                        <div><a href="<?= $target_url ?>"><img src="<?=$pic_path ?>" alt="<?= $project->title ?>"></a></div>
                        <h4><?= $percent_done ?>% complete</h4>
                        <div><?= anchor($target_url, 'Go To Training', $attributes) ?></div>
                    </div>
                <?php
        } ?>
    </div>
</section>

<section class="section-a">
    <h3>Downloads &amp; Resources</h3>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Consectetur assumenda animi ducimus tempora non quos eveniet perferendis ipsam corporis dolore quas pariatur culpa quis repellendus velit dolor recusandae sapiente ipsa, consequatur saepe sequi!</p>
</section>

