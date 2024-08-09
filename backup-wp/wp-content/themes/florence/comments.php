<?php if ( comments_open() ) : ?>
<div class="post-comments" id="comments_wrapper">
	
	<?php 
		if ( comments_open() ) :
		echo '<h4 class="block-heading">';
		comments_number(esc_html__('No Comments','florence'), esc_html__('1 Comment','florence'), '% ' . esc_html__('Comments','florence') );
		echo '</h4>';
		endif;

		echo "<div class='comments'>";
		
			wp_list_comments(array(
				'avatar_size'	=> 50,
				'max_depth'		=> 5,
				'style'			=> 'ul',
				'callback'		=> 'solopine_comments',
				'type'			=> 'all'
			));

		echo "</div>";

		echo "<div id='comments_pagination'>";
			paginate_comments_links(array('prev_text' => '&laquo;', 'next_text' => '&raquo;'));
		echo "</div>";

		$custom_comment_field = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';  //label removed for cleaner layout

		comment_form(array(
			'comment_field'			=> $custom_comment_field,
			'comment_notes_after'	=> '',
			'logged_in_as' 			=> '',
			'comment_notes_before' 	=> '',
			'title_reply'			=> esc_html__('Leave a Reply', 'florence'),
			'cancel_reply_link'		=> esc_html__('Cancel Reply', 'florence'),
			'label_submit'			=> esc_html__('Post Comment', 'florence')
		));
	 ?>


</div> <!-- end comments div -->
<?php endif; ?>