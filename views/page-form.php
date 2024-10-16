<?php
/**
 * Pages list options
 *
 * @package    Pages Lists
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

// Get static pages, not posts.
$static = buildStaticPages();

?>
<style>
.form-control-has-button {
	display: flex;
	align-items: center;
	flex-wrap: nowrap;
	gap: 0.25em;
	width: 100%;
	margin: 0;
	padding: 0;
}

#select-pages-wrap {
	margin-top: 1rem;
}
</style>

<fieldset class="mt-4">
	<legend class="screen-reader-text mb-3"><?php $L->p( 'Sidebar List Options' ) ?></legend>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="in_sidebar"><?php echo ucwords( $L->get( 'Sidebar List' ) ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="in_sidebar" name="in_sidebar">
				<option value="true" <?php echo ( $this->getValue( 'in_sidebar' ) === true ? 'selected' : '' ); ?>><?php $L->p( 'Enabled' ); ?></option>

				<option value="false" <?php echo ( $this->getValue( 'in_sidebar' ) === false ? 'selected' : '' ); ?>><?php $L->p( 'Disabled' ); ?></option>
			</select>
			<small class="form-text"><?php $L->p( 'Display a posts list in the sidebar ( <code>siteSidebar</code> hook required in the theme ).' ); ?></small>
		</div>
	</div>

	<div id="pages-lists-options" style="display: <?php echo ( $this->getValue( 'in_sidebar' ) == true ? 'block' : 'none' ); ?>;">

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for=""><?php $L->p( 'Form Label' ); ?></label>
			<div class="col-sm-10">
				<div class="form-control-has-button">
					<input type="text" id="label" name="label" value="<?php echo $this->getValue( 'label' ); ?>" placeholder="<?php echo $this->dbFields['label']; ?>" />
					<span class="btn btn-secondary btn-md button hide-if-no-js" onClick="$('#label').val('<?php echo $this->dbFields['label']; ?>');"><?php $L->p( 'Default' ); ?></span>
				</div>
				<small class="form-text text-muted"><?php $L->p( 'List title in the sidebar. Save as empty for no title.' ); ?></small>
			</div>
		</div>

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for="label_wrap"><?php $L->p( 'Label Wrap' ); ?></label>
			<div class="col-sm-10">
				<div class="form-control-has-button">
					<input type="text" id="label_wrap" name="label_wrap" value="<?php echo $this->getValue( 'label_wrap' ); ?>" placeholder="<?php $L->p( 'h2' ); ?>" />
					<span class="btn btn-secondary btn-md button hide-if-no-js" onClick="$('#label_wrap').val('<?php echo $this->dbFields['label_wrap']; ?>');"><?php $L->p( 'Default' ); ?></span>
				</div>
				<small class="form-text text-muted"><?php $L->p( 'Wrap the label in an element, such as a heading. Accepts HTML tags without brackets (e.g. h3), and comma-separated tags (e.g. span,strong,em). Save as blank for no wrapping element.' ); ?></small>
			</div>
		</div>

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for="list_view"><?php $L->p( 'List Direction' ); ?></label>
			<div class="col-sm-10">
				<select id="list_view" class="form-select" name="list_view">
					<option value="vert" <?php echo ( $this->getValue( 'list_view' ) === 'vert' ? 'selected' : '' ); ?>><?php $L->p( 'Vertical' ); ?></option>
					<option value="horz" <?php echo ( $this->getValue( 'list_view' ) === 'horz' ? 'selected' : '' ); ?>><?php $L->p( 'Horizontal' ); ?></option>
				</select>
				<small class="form-text text-muted"><?php $L->p( 'How to display the pages list.' ); ?></small>
			</div>
		</div>

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for="pages_limit"><?php $L->p( 'List Limit' ); ?></label>
			<div class="col-sm-10">
				<select id="pages_limit" class="form-select" name="pages_limit">
					<option value="al" <?php echo ( $this->getValue( 'pages_limit' ) === 'all' ? 'selected' : '' ); ?>><?php $L->p( 'All Pages' ); ?></option>
					<option value="select" <?php echo ( $this->getValue( 'pages_limit' ) === 'select' ? 'selected' : '' ); ?>><?php $L->p( 'Select Pages' ); ?></option>
				</select>
				<small class="form-text text-muted"><?php $L->p( 'Pages to display in the list.' ); ?></small>
			</div>
		</div>

		<div id="pages_select_wrap" class="form-field form-group row" style="display: <?php echo ( $this->getValue( 'pages_limit' ) === 'select' ? 'flex' : 'none' ); ?>;">
			<label class="form-label col-sm-2 col-form-label" for="pages_select"><?php $L->p( 'Select Pages' ); ?></label>

			<?php if ( isset( $static[0] ) ) : ?>
			<div class="col-sm-10">
				<p><?php $L->p( 'Which static pages shall display in the sidebar list.' ); ?></p>

				<div id="select-pages-wrap">

					<?php
					$count_p = 0;
					$count_c = 0;
					if ( $static ) :

						// Sort by position.
						usort( $static, function( $a, $b ) {
							return $a->position() > $b->position();
						} );

						foreach ( $static as $page ) :

						$relation = '';
						$title    = $L->get( 'Standalone page' );

						if ( $page->hasChildren() ) {

							$count_p++;
							$children    = $page->children();
							$child_names = [];
							foreach ( $children as $child ) {
								$child_names[] = $child->title();
							}
							asort( $child_names );
							$children = implode( ', ', $child_names );
							$relation = ' ' . $L->get( '(p)' );
							$title    = $L->get( 'Parent to ' ) . $children;

						} elseif ( $page->isChild() ) {

							$count_c++;
							$parent   = new \Page( $page->parentKey() );
							$relation = ' ' . $L->get( '(c)' );
							$title    = $L->get( 'Child of ' . $parent->title() );
						}

						if ( $page->key() === $site->homepage() ) {
							echo '';
						} elseif ( $page->slug() === str_replace( '/', '', $site->getField( 'uriBlog' ) ) ) {
							echo '';
						} elseif ( $page->slug() === $site->pageNotFound() ) {
							echo '';
						} else {
							printf(
								'<label class="check-label-wrap form-tooltip" for="page-%s" title="%s"><input type="checkbox" name="pages_select[]" id="page-%s" value="%s" %s /> %s%s</label>',
								$page->key(),
								$title,
								$page->key(),
								$page->key(),
								( is_array( $this->pages_select() ) && in_array( $page->key(), $this->pages_select() ) ? 'checked' : '' ),
								$page->title(),
								$relation
							);
						}
					endforeach; endif; ?>
				</div>
			</div>
			<?php else : ?>
			<div class="col-sm-10">
				<p><?php $L->p( 'Create at least one static page to display the page selection option.' ); ?></p>
			</div>
			<?php endif; ?>
		</div>
	</div>
</fieldset>
<script>
jQuery(document).ready( function($) {
	$( '#in_sidebar' ).on( 'change', function() {
		var show = $(this).val();
		if ( show == 'true' ) {
			$( "#pages-lists-options" ).fadeIn( 250 );
		} else if ( show == 'false' ) {
			$( "#pages-lists-options" ).fadeOut( 250 );
		}
	});
	$( '#pages_limit' ).on( 'change', function() {
		var show = $(this).val();
		if ( show == 'select' ) {
			$( "#pages_select_wrap" ).css( 'display', 'flex' );
		} else {
			$( "#pages_select_wrap" ).css( 'display', 'none' );
		}
	});
});
</script>
