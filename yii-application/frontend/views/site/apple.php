<?php foreach ($apples as $apple) { ?>
    <a role="button" class="popover-apple">
        <img src="/assets/apple.png" class="apple <?php echo $apple->isGood() ? $apple->color : 'blue' ?>" id="<?=$apple->id?>">
    </a>
<?php } ?>