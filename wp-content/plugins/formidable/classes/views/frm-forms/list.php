<div class="wrap">
    <div class="frmicon icon32"><br/></div>
	<h2 id="frm_form_page_header">
<?php
echo $params['template'] ? __( 'Templates', 'formidable' ) : __( 'Forms', 'formidable' );
if ( ! $params['template'] && current_user_can( 'frm_edit_forms' ) ) { ?>
        <a href="?page=formidable&amp;frm_action=new" class="add-new-h2"><?php _e( 'Add New', 'formidable' ); ?></a>
<?php
} ?>
    </h2>

<?php
require( FrmAppHelper::plugin_path() . '/classes/views/shared/errors.php' );
$wp_list_table->views();
?>

<form id="posts-filter" method="get">
    <input type="hidden" name="page" value="<?php echo esc_attr( $_GET['page'] ) ?>" />
    <input type="hidden" name="frm_action" value="list" />
<?php

$wp_list_table->search_box( __( 'Search', 'formidable' ), 'entry' );
$wp_list_table->display();

?>
</form>

</div>
