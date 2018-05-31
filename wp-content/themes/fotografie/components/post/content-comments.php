<?php
/**
 * Template part for displaying comments.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fotografie
 */

if ( comments_open() || get_comments_number() ) {
	comments_template();
}
