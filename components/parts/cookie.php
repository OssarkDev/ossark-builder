<?php 
$title = get_field('cookie_title', 'option');
$description = get_field('cookie_description', 'option');
?>

<?php if (!isset($_COOKIE['accept_cookies'])): ?>
    <div class="cookie-banner" id="cookie-banner">
        <div class="cookie-banner__description">
            <?php echo $description; ?>
        </div>
        <div class="cookie-banner__button">
            <button class="btn cookie" id="cookie-accept">Ok</button>
        </div>
    </div>
<?php endif; ?>