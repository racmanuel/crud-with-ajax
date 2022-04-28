<?php
/*
Plugin Name: CRUD with AJAX
 */
function crud_enqueue_scripts()
{
    wp_register_style('bulma-css', 'https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css', '1.0', 'all');
    wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . '/assets/js/custom.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-js', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'crud_enqueue_scripts');

add_shortcode('formulario', 'crud_formulario');
function crud_formulario()
{
    wp_enqueue_style('bulma-css');
    ?>
<form id="formulario" name="formulario" method="POST" action="">
<div class="field">
  <label class="label">Nombre</label>
  <div class="control">
    <input class="input" type="text" placeholder="e.g Alex Smith" name="name_customer" id="name_customer">
  </div>
</div>

<div class="field">
  <label class="label">Email</label>
  <div class="control">
    <input class="input" type="email" placeholder="e.g. alexsmith@gmail.com" name="email_customer" id="email_customer">
  </div>
</div>

<div class="field">
  <label class="label">Ciudad</label>
  <div class="control">
    <div class="select">
    <select name="estados" id="estados">
        <option selected="selected" value="">-- Seleciona una Ciudad --</option>
        <option value="SLP">San Luis Potosí</option>
        <option value="QRO">Queretaro</option>
    </select>
  </div>
</div>
<br>
<div class="control">
  <button type="submit" class="button is-primary">Enviar</button>
</div>
</form>
<br>
<article id="alerts" class="message">
  <div class="message-header">
  </div>
  <div class="message-body">
  </div>
</article>
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