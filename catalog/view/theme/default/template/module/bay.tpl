<div class="product_hit">
    <div class="module-title-green"><?php echo $heading_title; ?></div>


<div class="row product-layout ">
        <div class="padding">
            <?php foreach ($results as $item) { ?>
            <p><a href="<?php echo $item['feed_url']; ?>"><?php echo $item['seller']; ?></a></p>
            <p><?php echo $text_score." "; ?><?php echo $item['score']; ?></p>
            <p><?php echo $text_positive." "; ?><?php echo $item['positive_percent']."%"; ?></p>
            <p><?php echo $text_regdate." "; ?><?php echo $item['reg_date']; ?></p>

            <?php } ?>
        </div>
</div>
</div>
