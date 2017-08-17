<div class="latest_ins">

    <h6>Latest from Tagzie</h6>
    <?php
    query_posts('showposts=1');
    while (have_posts()) :
        the_post();
        ?>
        <p><?= get_the_excerpt(); ?> </p>
        <img src="<?= the_post_thumbnail_url() ?>" class="ncpss" alt="">
<?php endwhile; ?>
    <div class="redmoe">    
        <a href="<?= $app['base_url']; ?>blog">Read more on our blog</a>
    </div>
</div>