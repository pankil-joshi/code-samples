<script>
    var PostageOptions = <?= json_encode($postage_options); ?>;
</script>
<?php
if(!empty($postage_options)):
$counter = 1;
foreach ($postage_options as $index => $postage_option):
    ?>
    <div class="col-md-12 col-sm-12 postage-option-box postage-option-row">
        <div class="row">
            <div class="col-md-8">
                <div class="heading_prt">
                    <h6><?= $postage_option['label']; ?></h6>
                    <ul>
                        <li>Default price: <?= $currencies[$postage_option['rate_currency']]; ?><?= $postage_option['rate']; ?></li>
                        <?php
                        if ($postage_option['geography'] == '*') {

                            $geography = 'Worldwide';
                        } else {

                            $geography = array();

                            foreach ($continents as $continent) {

                                if (!empty($continent['child'])) {

                                    $allCountries = true;
                                    $tempGeography = array();
                                    foreach ($continent['child'] as $countryIndex => $country) {

                                        if (!in_array($country['countryCode'], explode(',', $postage_option['geography']))) {

                                            $allCountries = false;
                                        } else {

                                            if (!empty($countries[$country['countryCode']])) {

                                                $tempGeography[] = $countries[$country['countryCode']]['name'];
                                            }
                                        }

                                        if (count($continent['child']) == ($countryIndex + 1)) {

                                            if ($allCountries) {

                                                $geography[] = $continent['name'];
                                            } else {

                                                $geography = array_merge($geography, $tempGeography);
                                            }
                                        }
                                    }
                                }
                            }

                            $geography = implode(', ', $geography);
                        }
                        ?>
                        <li>Geography: <?= $geography; ?></li>
                        <li>Delivery time: <?= $postage_option['duration']; ?> days</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">

                <div class="btn_edit">

                    <div class="defult_btn postage_btns">
                        <div class="col-md-12 col-sm-12">
                            <button type="button" class="btn deflt edit-postage" data-index="<?= $index; ?>" data-id="<?= $postage_option['id']; ?>"><img src="<?= $app['base_assets_url']; ?>images/edit_postage.png" class="edit_post" alt=""> <p>Edit Postage<br>
                                    Option</p></button></div>
                        <div class="col-md-12 col-sm-12">
                            <button type="button" class="btn deflt delete-postage" data-index="<?= $index; ?>" data-id="<?= $postage_option['id']; ?>"><img src="<?= $app['base_assets_url']; ?>images/delete_postage.png" class="edit_post" alt="" data-id="<?= $postage_option['id']; ?>"><p>Delete Postage<br>
                                    Option</p></button></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 ruler"><div class="row"><hr></div></div>
    </div>

    <?php
endforeach;
$counter++;
else: ?>
    <p class="text-center"><img src="<?= $app['base_assets_url']; ?>images/no-data.png" alt="No data"> You've not yet created any postage option templates.</p>
<?php endif; ?>