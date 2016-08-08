<?php

//customize post excerpt
add_filter( 'get_the_excerpt', function ( $excerpt ) {
	preg_match( '%^([\S\s]{80,}?)\s%', $excerpt, $matches );
	$excerpt = $matches[1];
	if ( ! in_array( substr( $excerpt, - 1 ), [ '.', '?', '!' ] ) ) {
		$excerpt .= ' ...';
	}

	return $excerpt;
} );
