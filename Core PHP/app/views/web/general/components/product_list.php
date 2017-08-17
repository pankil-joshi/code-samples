<?php
foreach ($products as $key => $new_products):
    ?>   
    <a href="<?= $app['base_url']; ?><?= $new_products['instagram_username']; ?>/<?= $new_products['path']; ?>">
        <div class="hovereffect">
            <img class="img-responsive" src="<?= $new_products['image_thumbnail']; ?>images/<?= $new_products['image_thumbnail']; ?>" alt="">
            <div class="overlay">
                <div class="table_row">
                    <div class="table_cell">
                        <h2><span class="product-title-text"><?= $new_products['title']; ?></span>
                            <span class="product-price"><?= $new_products['prices']['min_price'] == $new_products['prices']['max_price'] ? $currencies[$new_products['base_currency_code']] . $new_products['prices']['min_price'] : $currencies[$new_products['base_currency_code']] . $new_products['prices']['min_price'] . " - " . $currencies[$new_products['base_currency_code']] . $new_products['prices']['max_price']; ?> </span> 
                        </h2>
                        @<?= $new_products['instagram_username']; ?>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php
endforeach;
?>