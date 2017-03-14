<?php
namespace Sharedrive;

class Breadcrumbs {

	public function render( $args = array() ) {

		if ( is_front_page() ) {
			return;
		}

		$post_id = Helpers::getPostId();

		$defaults  = array(
			'post_type'           => 'file',
			'taxonomy'            => 'directory',
			'separator_icon'      => '/',
			'breadcrumbs_id'      => 'breadcrumbs-wrap',
			'breadcrumbs_classes' => 'breadcrumb-trail breadcrumbs',
			'home_title'          => esc_html__( 'Shared Files', 'ignite' )
		);

		$args      = apply_filters( 'sd_breadcrumbs_args', wp_parse_args( $args, $defaults ) );
		$separator = '<span class="separator"> ' . esc_html( $args['separator_icon'] ) . ' </span>';

		// Open the breadcrumbs
		$html = '<div id="' . esc_attr( $args['breadcrumbs_id'] ) . '" class="' . esc_attr( $args['breadcrumbs_classes'] ) . '">';

		// Add Post Type archive link & separator (always present)
		$html .= '<span class="item-home"><a class="bread-link bread-home" href="' . esc_url( get_post_type_archive_link( $args['post_type'] ) ) . '" title="' . esc_attr( $args['home_title'] ) . '">' . esc_html( $args['home_title'] ) . '</a></span>';

		$html .= $separator;

		// Post
		if ( is_singular( 'post' ) ) {
			
			$category = get_the_category( $post_id );
			$category_values = array_values( $category );
			$last_category = end( $category_values );
			$cat_parents = rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );
			$cat_parents = explode( ',', $cat_parents );

			foreach ( $cat_parents as $parent ) {
				$html .= '<span class="item-cat">' . wp_kses( $parent, wp_kses_allowed_html( 'a' ) ) . '</span>';
				$html .= $separator;
			}

			$html .= '<span class="item-current item-' . $post_id . '"><span class="bread-current bread-' . $post_id . '" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></span>';

		} elseif ( is_tax( $args['taxonomy'] ) ) {

			$custom_tax_name = get_queried_object()->name;

			$taxonomy = get_queried_object()->taxonomy;

			$ancestors = get_ancestors( get_queried_object()->term_id, $taxonomy );
			
			if ( ! empty ( $ancestors ) ) {
				$ancestors = array_reverse( $ancestors );
			}

			foreach( $ancestors as $ancestor_id ) {
				$ancestor = get_term_by('id', $ancestor_id, $taxonomy );
				$ancestor_link = get_term_link(  $ancestor_id, $taxonomy );

				$html .= '<span class="item-current item-archive"><span class="bread-current bread-archive"><a href="'.esc_url($ancestor_link).'" title="">' .  esc_html( $ancestor->name ) . '</a></span>' . $separator;
			}

			$html .= '<span class="item-current item-archive"><span class="bread-current bread-archive">' . esc_html( $custom_tax_name ) . '</span></span>';

		}

		$html .= '</div>';

		$html = apply_filters( 'sd_breadcrumbs_filter', $html );

		echo wp_kses_post( $html );

	}
}
