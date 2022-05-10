<?php
/*
Plugin Name: CRUD with AJAX
 */
function crud_enqueue_scripts()
{
    wp_register_style('bulma-css', 'https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css', '1.0', 'all');
    wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'assets/js/custom.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-js', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    /** Add a Datatables */
    wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', '1.0', 'all');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('datatables-2', 'https://cdn.datatables.net/1.11.5/js/dataTables.bulma.min.js', array('jquery'), '1.0', true);
    wp_enqueue_style('datatables-bulma', 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css', '1.0', 'all');
    wp_enqueue_style('datatables-bulma-2', 'https://cdn.datatables.net/1.11.5/css/dataTables.bulma.min.css', '1.0', 'all');
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
      <input class="input" type="email" placeholder="e.g. alexsmith@gmail.com" name="email_customer"
        id="email_customer">
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

function crud_edit()
{
    global $wpdb;

    $id = $_GET['id'];
    echo $id;

    $table_name = $wpdb->prefix . 'crud';

    $consulta = $wpdb->get_results("SELECT * FROM $table_name WHERE ID = $id ");

    foreach ($consulta as $data) {
        # code...
        ?>
<form id="formulario-edit" name="formulario-edit" method="POST" action="">
  <input type="number" hidden id="id" value="<?php echo $id; ?>">
  <div class="field">
    <label class="label">Nombre</label>
    <div class="control">
      <input class="input" type="text" placeholder="e.g Alex Smith" name="name_customer" id="name_customer"
        value="<?php echo $data->nombre; ?>">
    </div>
  </div>

  <div class="field">
    <label class="label">Email</label>
    <div class="control">
      <input class="input" type="email" placeholder="e.g. alexsmith@gmail.com" name="email_customer" id="email_customer"
        value="<?php echo $data->email; ?>">
    </div>
  </div>

  <div class="field">
    <label class="label">Ciudad</label>
    <div class="control">
      <div class="select">
        <select name="estados" id="estados">
            <?php if($data->ciudad == 'SLP'): ?>
            <option value="SLP" selected>San Luis Potosí</option>
            <?php else: ?>
            <option value="SLP">San Luis Potosí</option>
            <?php endif; ?>
            <?php if($data->ciudad == 'QRO'): ?>
            <option value="QRO" selected>Queretaro</option>
            <?php else: ?>
            <option value="QRO">Queretaro</option>
            <?php endif; ?>
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
}

add_shortcode('formulario-editar', 'crud_edit');

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

/**
 * Update a Record
 */

function update_custom_form()
{
// Nuestro código de manipulación de los datos
    global $wpdb;

    $table_name = $wpdb->prefix . 'crud';

    $id_customer = $_POST['id_customer'];
    $name_customer = $_POST['name_customer'];
    $email_customer = $_POST['email_customer'];
    $city_customer = $_POST['estados'];

    $wpdb->update(
        $table_name,
        array(
            'nombre' => $name_customer,
            'email' => $email_customer,
            'ciudad' => $city_customer,
        ),
        array(
          'ID' => $id_customer
        )
    );

//wp_redirect(site_url('/')); // <-- here goes address of site that user should be redirected after submitting that form
    wp_die();
}
add_action('wp_ajax_nopriv_update_custom_form', 'update_custom_form'); // Para usuarios no logueados
add_action('wp_ajax_update_custom_form', 'update_custom_form'); // Para usuarios logueados

/**
 * Delete a Record
 */

function delete_custom_form()
{
// Nuestro código de manipulación de los datos
    global $wpdb;

    $table_name = $wpdb->prefix . 'crud';

    $id_customer = $_GET['id_customer'];

    $wpdb->delete(
        $table_name,
        array(
          'ID' => $id_customer
        )
    );

//wp_redirect(site_url('/')); // <-- here goes address of site that user should be redirected after submitting that form
    wp_die();
}
add_action('wp_ajax_nopriv_delete_custom_form', 'delete_custom_form'); // Para usuarios no logueados
add_action('wp_ajax_delete_custom_form', 'delete_custom_form'); // Para usuarios logueados

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

function crud_read_results()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'crud';

    $registros = $wpdb->get_results("SELECT  * FROM $table_name");

    ?>
<table id="example" class="table is-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Correo Electronico</th>
      <th>Ciudad</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
foreach ($registros as $data) {
        echo "<tr>";
        echo "<td>" . $data->id . "</td>";
        echo "<td>" . $data->nombre . "</td>";
        echo "<td>" . $data->email . "</td>";
        echo "<td>" . $data->ciudad . "</td>";
        echo "<td></td>";
        echo "</tr>";
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Correo Electronico</th>
      <th>Ciudad</th>
      <th>Opciones</th>
    </tr>
  </tfoot>
</table>
<?php

}
add_shortcode('form_select', 'crud_read_results');