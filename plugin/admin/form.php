<?php
/**
 * @package Page Métrics
 * @version 1.5.1
 */
defined('ABSPATH') or exit();

class slwsu_page_metrics_admin_form {

    public static function validation() {
        if (isset($_GET['settings-updated'])) {
            delete_transient('slwsu_page_metrics_options');
            ?>
            <div id="message" class="updated">
                <p><strong><?php echo __('Settings saved', 'pmtx') ?></strong></p>
            </div>
            <?php
        }
    }

    public static function action() {
        ?>
        <a class="page-metrics-modal-link" style="text-decoration:none; font-weight:bold;" href="#openModal"><?php echo __('About', 'pmtx'); ?> <span class="dashicons dashicons-info"></span></a>
        <?php
    }

    public static function message($post) {
        ?>
        <div id="openModal" class="page-metrics-modal">
            <div>
                <a href="#page-metrics-modal-close" title="Close" class="page-metrics-modal-close"><span class="dashicons dashicons-dismiss"></span></a>
                <h2><?php echo __('About', 'pmtx'); ?></h2>
                <p><span class="dashicons dashicons-admin-users"></span> <?php echo __('By', 'pmtx'); ?> <?php echo 'Steeve Lefebvre - slWsu'; ?></p>
                <p><span class="dashicons dashicons-admin-site"></span> <?php echo __('More information', 'pmtx'); ?> : <a href="<?php echo 'https://web-startup.fr/page-metrics/'; ?>" target="_blank"><?php _e('plugin page', 'pmtx'); ?></a></p>
                <p><span class="dashicons dashicons-admin-tools"></span> <?php echo __('Development for the web', 'pmtx'); ?> : HTML, PHP, JS, WordPress</p>
                <h2><?php echo __('Support', 'pmtx'); ?></h2>
                <p><span class="dashicons dashicons-email-alt"></span> <?php echo __('Ask your question', 'pmtx'); ?></p>
                <?php
                if (isset($post['submit'])) {
                    global $current_user; $to = 'steeve.lfbvr@gmail.com'; $subject = "Support Grouper !!!";
                    $roles = implode(", ", $current_user->roles);
                    $message = "From: " . get_bloginfo('name') . " - " . get_bloginfo('home') . " - " . get_bloginfo('admin_email') . "\n";
                    $message .= "By : " . strip_tags($post['nom']) . " - " . $post['email'] . " - " . $roles . "\n";
                    $message .= strip_tags($post['message']) . "\n";
                    if (wp_mail($to, $subject, $message)):
                        echo '<p class="page-metrics-contact-valide"><strong>' . __('Your message has been sent !', 'pmtx') . '</strong></p>';
                    else:
                        echo '<p class="page-metrics-contact-error">' . __('Something went wrong, go back and try again !', 'pmtx') . '</p>';
                    endif;
                }
                ?>
                <form id="page-metrics-contact" action="" method="post">
                    <fieldset>
                        <input id="nom" name="nom" type="text" placeholder="<?php echo __('Your name', 'pmtx'); ?>" required="required">
                    </fieldset>
                    <fieldset>
                        <input id="email" name="email" type="email" placeholder="<?php echo __('Your Email Address', 'pmtx'); ?>" required="required">
                    </fieldset>
                    <fieldset>
                        <textarea id="message" name="message" placeholder="<?php echo __('Formulate your support request or feature proposal here...', 'pmtx'); ?>" required="required"></textarea>
                    </fieldset>
                    <fieldset>
                        <input id="submit" name="submit" type="submit" value="<?php echo __('Send', 'pmtx'); ?>" class="button button-primary" type="submit" id="page-metrics-contact-submit" data-submit="...Sending" />
                    </fieldset>
                </form>
            </div>
        </div>
        <?php
    }

}
