<?php
/**
 * Login / Register Form Template
 * Overrides: woocommerce/templates/myaccount/form-login.php
 *
 * Shown when user is not logged in and visits the My Account page.
 * Login form is always shown; registration form is conditional on WC settings.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form'); ?>

<section class="account-login">
    <div class="container">

        <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

        <!-- Two-column: Login + Register -->
        <div class="row" id="customer_login">

            <!-- Login -->
            <div class="col-6 col-sm-12">
                <div class="account-login__form">
        <?php else : ?>
        <div class="row">
            <div class="col-6-offset-3 col-sm-12">
                <div class="account-login__form">
        <?php endif; ?>

                    <h2><?php esc_html_e('Login', 'woocommerce'); ?></h2>

                    <form class="woocommerce-form woocommerce-form-login login" method="post">

                        <?php do_action('woocommerce_login_form_start'); ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
                        </p>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
                        </p>

                        <?php do_action('woocommerce_login_form'); ?>

                        <p class="form-row">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
                            </label>
                        </p>

                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

                        <button type="submit" class="btn woocommerce-button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>

                        <p class="woocommerce-LostPassword lost_password">
                            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
                        </p>

                        <?php do_action('woocommerce_login_form_end'); ?>

                    </form>

                </div>
            </div>

            <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

            <!-- Register -->
            <div class="col-6 col-sm-12">
                <div class="account-login__form">

                    <h2><?php esc_html_e('Register', 'woocommerce'); ?></h2>

                    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

                        <?php do_action('woocommerce_register_form_start'); ?>

                        <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
                            </p>
                        <?php endif; ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" />
                        </p>

                        <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                            </p>
                        <?php else : ?>
                            <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
                        <?php endif; ?>

                        <?php do_action('woocommerce_register_form'); ?>

                        <p class="woocommerce-form-row form-row">
                            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                            <button type="submit" class="btn woocommerce-button woocommerce-form-register__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                        </p>

                        <?php do_action('woocommerce_register_form_end'); ?>

                    </form>

                </div>
            </div>

        </div>

        <?php else : ?>

        </div>

        <?php endif; ?>

    </div>
</section>

<?php do_action('woocommerce_after_customer_login_form'); ?>
