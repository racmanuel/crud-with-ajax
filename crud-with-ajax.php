<?php
/*
Plugin Name: CRUD with AJAX
 */
function crud_enqueue_scripts()
{
    wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . '/assets/js/custom.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-js', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'crud_enqueue_scripts');

add_shortcode('formulario', 'crud_formulario');
function crud_formulario()
{
    ?>
   <form id="formulario" name="formulario" method="POST" action="">
    <label for="name_customer">Nombre:</label>
    <br>
    <input type="text" class="form-control" name="name_customer" id="name_customer">
    <br>
    <label for="email_customer">Email:</label>
    <br>
    <input type="email" name="email_customer" id="email_customer">
    <br>
    <label for="estados">Ciudad/Estado</label>
    <br>
    <select name="estados" id="estados">
        <option selected="selected" value="">-- Seleciona una Ciudad --</option>
        <option value="SLP">San Luis Potosí</option>
        <option value="QRO">Queretaro</option>
    </select>
    <br>
    <input type="submit" value="submit" />
</form>
<div id="alert">
</div>
    <?php
}

/**
 * Funcion que captura los valores de una
 * petición POST o GET de HTTP.
 */
function my_save_custom_form()
{
    // Nuestro código de manipulación de los datos
    global $wpdb;

    $table_name = $wpdb->prefix . 'crud';

    $name_customer = $_POST['name_customer'];
    $email_customer = $_POST['email_customer'];
    $city_customer = $_POST['estados'];

    $wpdb->insert(
        $table_name,
        array(
            'nombre' => $name_customer,
            'email' => $email_customer,
            'ciudad' => $city_customer,
        )
    );

    //wp_redirect(site_url('/')); // <-- here goes address of site that user should be redirected after submitting that form
    wp_die();
}

add_action('wp_ajax_nopriv_my_save_custom_form', 'my_save_custom_form'); // Para usuarios no logueados
add_action('wp_ajax_my_save_custom_form', 'my_save_custom_form'); // Para usuarios logueados

global $crud_db_version;
$crud_db_version = '1.0';

function crud_install()
{
    global $wpdb;
    global $crud_db_version;

    $table_name = $wpdb->prefix . 'crud';

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `nombre` VARCHAR(255) NOT NULL COLLATE 'utf8_bin',
        `email` VARCHAR(255) NOT NULL COLLATE 'utf8_bin',
        `ciudad` VARCHAR(255) NOT NULL COLLATE 'utf8_bin',
        PRIMARY KEY (`id`) USING BTREE
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    add_option('crud_db_version', $crud_db_version);
}
register_activation_hook(__FILE__, 'crud_install');
